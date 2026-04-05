## **Requirements for FutureSoft Pvt Ltd Project Management System**

---

# **1. User Roles and Access Levels**

### **1.1 User Roles**

The system shall support the following user roles:

1. **Owner (Company Owner)** – Highest authority
2. **Manager** – Administrative authority under Owner
3. **Project Manager (PM)** – Operational project-level authority
4. **Developer (Dev)** – Execution-level user

### **1.2 User Categories**

* **Admin-Level Users**: Owner, Manager
* **Employees**: PM, Developer

### **1.3 Access Control**

1. The system shall authenticate users before granting access.
2. The system shall authorize users based on role-based access control (RBAC).
3. The system shall restrict actions based on assigned roles.

---

# **2. Project Management**

## **2.1 Functional Requirements**

### **Project Profile Management**

2.1.1 The system shall allow the Owner to create a project profile with client information and project plan.
2.1.2 The system shall allow the Owner to add and update project details.
2.1.3 The system shall allow the Owner to define scheduled dates (delivery date, deadlines).
2.1.4 The system shall allow the Owner to categorize projects (Local / Foreign).
2.1.5 The system shall allow the Owner to manage (update/delete) project profiles.
2.1.6 The system shall allow authorized users to view project profiles.

### **Project Status Management**

2.1.7 The system shall support project statuses:

* Initiated
* In Progress
* On Hold
* Completed
* Cancelled

### **Project Timeline Management**

2.1.8 The system shall allow setting project milestones.
2.1.9 The system shall display scheduled milestones.
2.1.10 The system shall record actual milestone durations.
2.1.11 The system shall allow marking milestones as completed.

### **Project Costing**

2.1.12 The system shall allow adding cost factors.
2.1.13 The system shall calculate employee cost:

> Employee Cost = Hourly Rate × Time Spent
> 2.1.14 The system shall calculate total project cost.
> 2.1.15 The system shall allow adding project income.
> 2.1.16 The system shall calculate profit by deducting income from cost.
> 2.1.17 The system shall allow listing and filtering income and costs.

### **Project Progress Tracking**

2.1.18 The system shall divide projects into phases.
2.1.19 The system shall calculate phase completion percentage:

> Completion % = (Completed Tasks / Total Tasks) × 100

2.1.20 The system shall display progress per phase.
2.1.21 The system shall track task states:

* Pending
* Submitted
* Delayed-Pending
* Delayed-Submitted

2.1.22 The system shall allow viewing project progress by phases.
2.1.23 The system shall calculate total estimated project time (if all tasks are estimated).
2.1.24 Show recently completed tasks.
2.1.25 Show upcoming tasks nearing deadlines.
2.1.26 Show overdue tasks.

---

## **2.2 Authorization Matrix**

| Action         | Owner | Manager | PM           | Dev          |
| -------------- | ----- | ------- | ------------ | ------------ |
| Create Project | ✔     | ✖       | ✖            | ✖            |
| Manage Project | ✔     | ✖       | ✖            | ✖            |
| View Project   | ✔     | ✔       | ✔            | ✔ (Assigned) |
| Manage Costing | ✔     | ✔       | ✔ (Assigned) | ✖            |
| View Progress  | ✔     | ✔       | ✔            | ✔(Assigned)  |
| View Estimates | ✔     | ✔       | ✔            | ✖(Assigned)  |

---

# **3. Task Management**

## **3.1 Functional Requirements**

3.1.1 The system shall allow PM to divide projects into tasks (maximum 2 levels).
3.1.2 The system shall allow PM to define estimated time and delivery date.
3.1.3 The system shall allow Developers to:

* Submit task status (Done / Cannot Complete)
* Record time spent
* Add comments

3.1.4 The system shall allow users to view task details and delivery status.
3.1.5 The system shall support task priority levels:

* High
* Medium
* Low

3.1.6 The system shall support task sorting and filtering.
3.1.7 The system shall allow file attachments (documents, screenshots, specifications).

---

## **3.2 Authorization Matrix**

| Action             | Owner | Manager | PM | Dev |
| ------------------ | ----- | ------- | -- | --- |
| Create Tasks       | ✖     | ✖       | ✔  | ✖   |
| Assign Tasks       | ✖     | ✖       | ✔  | ✖   |
| Update Task Status | ✖     | ✖       | ✖  | ✔   |
| View Tasks         | ✔     | ✔       | ✔  | ✔   |

---

# **4. Timesheet Management**

## **4.1 Functional Requirements**

4.1.1 Users (PM, Dev) shall submit monthly timesheets.
4.1.2 Managers and Owners shall approve timesheets.
4.1.3 The system shall allow viewing historical timesheets.
4.1.4 The system shall support filtering by month.
4.1.5 The system shall allow marking leave days in timesheets.

---

## **4.2 Authorization Matrix**

| Action            | Owner | Manager | PM      | Dev     |
| ----------------- | ----- | ------- | ------- | ------- |
| Submit Timesheet  | ✖     | ✖       | ✔       | ✔       |
| Approve Timesheet | ✔     | ✔       | ✖       | ✖       |
| View Timesheets   | ✔     | ✔       | ✔ (Own) | ✔ (Own) |

---

# **5. User Management**

## **5.1 Functional Requirements**

### **User Account Management**

5.1.1 The system shall view, create, update, delete user accounts.
5.1.2 The system shall allow managing working/resigned status.
5.1.3 The system shall allow enabling/disabling accounts.

### **Profile Management**

5.1.4 Users shall manage their personal information (except username).
5.1.5 Users shall manage profile pictures.

### **Employee Data Management**

5.1.6 The system shall store:

* Salary
* Hourly Rate
* EPF/ETF details
* Education
* Skills

5.1.7 Hourly rate shall be calculated as:

> Monthly Salary / (22 × 8 hours)

### **Employee designation Management**

5.1.8 The system shall manage designation hierarchy.
5.1.9 The system shall manage designation and sub-designation details.
5.1.10 The system shall assign designations to users.

---

## **5.2 Authorization Matrix**

| Action              | Owner  | Manager       | PM  | Dev   |
| ------------------- | ------ | -----------   | --- | ---   |
| Manage All Users    | ✔     | ✔ (limited) | ✖  | ✖   |
| Manage Own Profile  | ✔     | ✔           | ✔  | ✔   |
| Manage Designations | ✔     | ✔ (limited) | ✖  | ✖   |

---

# **6. Communication**

## **6.1 Functional Requirements**

6.1.1 Users shall send private messages.
6.1.2 Messages shall support file attachments.
6.1.3 The system shall support project-based discussion threads.
6.1.4 The system shall support task-based discussion threads.

---

## **6.2 Authorization Matrix**

| Action            | Owner | Manager | PM | Dev          |
| ----------------- | ----- | ------- | -- | ------------ |
| Private Messaging | ✔     | ✔       | ✔  | ✔            |
| Project Threads   | ✔     | ✔       | ✔  | ✔ (Assigned) |
| Task Threads      | ✔     | ✔       | ✔  | ✔ (Assigned) |

---

# **7. Reporting**

## **7.1 Functional Requirements**

7.1.1 View project assignment durations.
7.1.2 View employee project-wise time tracking.
7.1.3 View designation-wise time tracking.
7.1.4 Generate developer efficiency reports.
7.1.5 Display yearly project timeline calendar.
7.1.6 Show:

* Active projects
* Completed projects
* Delayed projects
* Task summaries

### **Calendar**

7.1.7 Display deadlines (daily, weekly, monthly).

---

## **7.2 Authorization Matrix**

| Action       | Owner | Manager | PM | Dev |
| ------------ | ----- | ------- | -- | --- |
| View Reports | ✔     | ✔       | ✔  | ✖   |

---

# **8. Leave Management**

## **8.1 Functional Requirements**

8.1.1 Users (PM, Dev) shall apply for leave.
8.1.2 Users shall cancel leave requests.
8.1.3 Managers shall approve/reject leave requests.
8.1.4 The system shall track leave types:

* Medical (15)
* Casual (10)
* Annual (10)

8.1.5 The system shall support filtering by date and employee.
8.1.6 The system shall provide a leave calendar view.

---

## **8.2 Authorization Matrix**

| Action        | Owner | Manager | PM | Dev |
| ------------- | ----- | ------- | -- | --- |
| Apply Leave   | ✖     | ✖       | ✔  | ✔   |
| Approve Leave | ✔     | ✔       | ✖  | ✖   |
| View Leaves   | ✔     | ✔       | ✔(own)  | ✔(own)   |

---

# **9. Resource Allocation**

## **9.1 Functional Requirements**

9.1.1 Assign PM to projects.
9.1.2 Notify assigned PM.
9.1.3 Validate PM availability before assignment.

9.1.4 Assign Developers to projects.
9.1.5 Notify assigned Developers.
9.1.6 Validate Developer availability.

9.1.7 Assign tasks to Developers.
9.1.8 Validate:

* Leave overlaps
* Task conflicts

9.1.9 Provide resource availability chart.
9.1.10 Provide workload reports.

---

## **9.2 Authorization Matrix**

| Action        | Owner | Manager | PM | Dev |
| ------------- | ----- | ------- | -- | --- |
| Assign PM     | ✔     | ✔       | ✖  | ✖   |
| Assign Dev    | ✖     | ✖       | ✔  | ✖   |
| Assign Tasks  | ✖     | ✖       | ✔  | ✖   |
| View Workload | ✔     | ✔       | ✔  | ✖   |
