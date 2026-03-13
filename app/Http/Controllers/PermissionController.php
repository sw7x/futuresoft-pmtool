<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Models\Permission as PermissionModel;
use App\Models\role as RoleModel;
use Sentinel;
use App\Common\Utils\AlertDataUtil;
use App\Exceptions\CustomException;
use App\Common\SharedServices\RoleSharedService;
            //return redirect()->route('view-cart')->with(AlertDataUtil::success('Successfully applied coupon code'));

            //return redirect()->route('view-cart')->with(AlertDataUtil::error($e->getMessage()));


class PermissionController extends Controller
{
    public function loadPermissions(Request $request){
    
        // Get the role_id from the request
        $roleName = $request->input('role');
        $roleArr  = RoleModel::getAllRoleInfo();
        
        $selRoleId = RoleSharedService::getRoleIdByName($roleName) ?? $roleArr[0]['id'];

        //$permissions = PermissionModel::all()->toArray();
        $permissions = PermissionModel::where('role_id', $selRoleId)->get()->toArray();

        return view('permissions')->with([
            'permissions'  => $permissions,
            'roleArr'      => $roleArr,
            'selRoleId'    => $selRoleId
        ]);
    }   





    public function storePermission(Request $request)
    {
        //dd($request->all());
        
        // Validation rules
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:35',
            'key'       => 'required|string|max:35',
            'role_id'   => 'required|integer|exists:roles,id',
            'parent_id' => 'nullable|integer|exists:permissions,id',
            'access'    => 'required|in:allow,deny',
            'status'    => 'required|in:true,false,1,0'
        ]);
        
        $roleName = RoleSharedService::getRoleNameById((int) $request->input('role_id'));
        if ($validator->fails()) {
            $errorList = '<ul><li>' . implode('</li><li>', $validator->errors()->all()) . '</li></ul>';
            return redirect()->route('permissions.index',['role' => $roleName])
                             ->with(AlertDataUtil::error('Validation Failed:', ['message2' => $errorList]));
        }

        

        // Extract and convert data
        $role_id    = (int) $request->input('role_id');
        $status     = ($request->input('status') === 'true' || $request->input('status') === '1' || $request->input('status') === true);
        $parent_id  = $request->input('parent_id') ? (int) $request->input('parent_id') : null;

        $data = [
            'name'      => $request->input('name'),
            'key'       => $request->input('key'),
            'parent_id' => $parent_id,
            'access'    => $request->input('access'),
            'role_id'   => $role_id,
            'status'    => $status,
        ];


        try {
            // Insert the record
            $inserted = PermissionModel::create($data);

            if ($inserted) {
                return redirect()->route('permissions.index',['role' => $roleName])->with(AlertDataUtil::success('Root permission successfully created.'));
            } else {
                return redirect()->route('permissions.index',['role' => $roleName])->with(AlertDataUtil::error('Failed to create root permission.'));
            }
        } catch (\Exception $e) {
            // Handle any exceptions during insertion
            return redirect()->route('permissions.index',['role' => $roleName])->with(AlertDataUtil::error('Error: ' . $e->getMessage()));
        }
    }
//$roleName = RoleSharedService::getRoleNameById($role_id);
    public function deletePermission(Request $request, int $id)
    {
        try {
            if (!filter_var($id, FILTER_VALIDATE_INT)) {
                throw new CustomException('Invalid id');
            }

            $validator = Validator::make($request->all(), [
                'role_id'   => 'required|integer|exists:roles,id',
                'parent_id' => 'nullable|integer|exists:permissions,id',
            ]);

            $roleName = RoleSharedService::getRoleNameById((int) $request->input('role_id'));
            if ($validator->fails()) {
                $errorList = '<ul><li>' . implode('</li><li>', $validator->errors()->all()) . '</li></ul>';
                return redirect()->route('permissions.index',['role' => $roleName])
                                 ->with(AlertDataUtil::error('Validation Failed:', ['message2' => $errorList]));
            }

            // Retrieve all children IDs recursively
            $data       = PermissionModel::getAllChildrenById($id)->toArray();
            $dbRecIds   = collect($data)->pluck('db_rec_id')->toArray();
            $dbRecIds[] = $id; // Include the parent ID itself
            $dbRecIds   = array_unique($dbRecIds);

        
            DB::beginTransaction();
            PermissionModel::whereIn('id', $dbRecIds)->delete();
            DB::commit();

            return redirect()->route('permissions.index',['role' => $roleName])
                             ->with(AlertDataUtil::success('Permission(s) deleted successfully.'));

        }catch (CustomException $e) {
            if (DB::transactionLevel() > 0) DB::rollBack();
            return redirect()->route('permissions.index',['role' => $roleName])
                             ->with(AlertDataUtil::error('Failed to delete permission(s). ' . $e->getMessage()));
        }catch (\Exception $e) {
            if (DB::transactionLevel() > 0) DB::rollBack();
            return redirect()->route('permissions.index',['role' => $roleName])
                             ->with(AlertDataUtil::error('Failed to delete permission(s). '));
        }
    }

    public function updatePermissions(Request $request)
    {
        try {
            // Get data from request and ensure they are integer arrays
            $denyDbRecs  = $request->input('denyDbRecs') ? array_map('intval', (array)$request->input('denyDbRecs')) : [];
            $allowDbRecs = $request->input('allowDbRecs') ? array_map('intval', (array)$request->input('allowDbRecs')) : [];
            $roleName = RoleSharedService::getRoleNameById((int) $request->input('role_id'));


            DB::beginTransaction();

            // Batch update to 'deny'
            if (!empty($denyDbRecs)) {
                PermissionModel::whereIn('id', $denyDbRecs)->update(['access' => 'deny']);
            }

            // Batch update to 'allow'
            if (!empty($allowDbRecs)) {
                PermissionModel::whereIn('id', $allowDbRecs)->update(['access' => 'allow']);
            }

            DB::commit();

            return redirect()->route('permissions.index', ['role' => $roleName])
                             ->with(AlertDataUtil::success('Permissions updated successfully.'));

        } catch (\Exception $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            return redirect()->route('permissions.index', ['role' => $roleName])
                             ->with(AlertDataUtil::error('Failed to update permissions. '));
                             //->with(AlertDataUtil::error('Failed to update permissions. ' . $e->getMessage()));
        }
    }

}
