# UI

## Blocks

### Menu

1. Home
2. Task Archive
3. Contests
4. About
5. FAQ

### Authentication/Personal

If user is not authenticated:

1. Login Form
2. Register Form

Otherwise:

1. User Profile
2. Settings
3. Logout

### List of Tasks from Contest

1. Task Name
2. Task Description (Excerpt) (_optional_)

### Contests List Block

1. Contest Table
   1. Contest ID
   2. Contest Name (Link to Contest)
   3. Contest Description (Excerpt)
   4. Contest Start Date
   5. Contest End Date

### Contest View Block

If user is not authenticated show:

1. Contest Description
2. Contest Start Date
3. Contest End Date

If user is authenticated, but not enrolled:

1. Contest Description
2. Contest Start Date
3. Contest End Date
4. Enroll Button (if contest is enrollable)

If user is authenticated and enrolled:

1. Contest Description
2. Contest Start Date
3. Contest End Date
4. [List of Tasks from Contest](#list-of-tasks-from-contest)

If user is contest manager:

1. Contest Description
2. Contest Start Date
3. Contest End Date
4. [List of Tasks from Contest](#list-of-tasks-from-contest)
5. Task Management (Add/Edit/Delete)
6. User Management (Enroll/Unenroll)
7. Contest Management (Edit/Delete)

If user is administrator:

1. Contest Description
2. Contest Start Date
3. Contest End Date
4. [List of Tasks from Contest](#list-of-tasks-from-contest)
5. Task Management (Add/Edit/Delete)
6. User Management (Enroll/Unenroll)
7. Contest Management (Create/Edit/Delete)

### Task View Block

Task can be private or public. If task is private, it is only visible to users who are enrolled in the contest, or created the task. If task is public, it is visible to all users.

If user is not authenticated:

1. Task Details
   1. Task Name
   2. Task Description
   3. Task Requirements (Time/Memory)
   4. Task Input Format
   5. Task Output Format
   6. Task Example Input
   7. Task Example Output
   8. Task Example Explanation
   9. Task Author
2. Login Button

If user is authenticated:

1. Task Details
   1. Task Name
   2. Task Description
   3. Task Requirements (Time/Memory)
   4. Task Input Format
   5. Task Output Format
   6. Task Example Input
   7. Task Example Output
   8. Task Example Explanation
   9. Task Author
2. Textarea for submission
3. Or file input for submission
4. Submit Button
5. View Submission List (in table format)
   1. Submission ID
   2. Submission Date & Time
   3. Submission Status
   4. Submission Score
   5. Reference to the submission code

## Pages

### Home Page

### Contests List Page

1. [Menu](#menu)
2. [Authentication/Personal](#authenticationpersonal)
3. [Contests List Block](#contests-list-block)

### Contest Page

1. [Menu](#menu)
2. [Authentication/Personal](#authenticationpersonal)
3. [Contest View Block](#contest-view-block)

If user is enrolled in the contest:

1.
1. [Task View Block](#task-view-block)

### Task Archive Page

Task Archive is a special contest that contains all tasks from contest with `id 0`. It is used to show all tasks in a single place. It is not shown in the contest list.

All users are enrolled in this contest. It is not possible to unenroll from this contest.
