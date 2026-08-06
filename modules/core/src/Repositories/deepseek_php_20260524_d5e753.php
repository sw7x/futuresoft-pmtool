// src/SharedKernel/Contracts/Repository.php
<?php
namespace SharedKernel\Contracts;

interface Repository
{
    public function save($entity): void;
    public function findById(string $id);
    public function delete($entity): void;
    public function exists(string $id): bool;
}