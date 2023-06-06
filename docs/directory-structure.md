# Directory Structure

* data
  * tasks
    * {task_id}
      * tests
      * checkers
      * modules
  * contests
    * {contest_id}
      * task\_{task_id}
  * user
    * {user_id}
  * tmp
    * {worker_id}
      * user_solution
      * compiled_solution
      * input_data
      * output_data
      * pattern_data
  * logs

## `data` directory
Data directory stores static information about tasks, contests, users and other private files.

## `tasks` directory
Task directory stores files for tasks.
* `{task_id}` — single task directory
  * `/tests` — task [tests](ioi-scoring.md)
  * `/checkers` — task checkers (e.g. programm for checking task)
  * `/modules`  — additional resources included during compilation

## `contests` directory
Contest directory stores files received from contest.
* `{contest_id}` — single contest directory
  * `/task_{task_id}` — stores solved user tasks. Format: `task_{task_id}_{user_id}_{subimssion_number}.{ext}`

## `tmp` directory
tmp directory stores tmp files (e.g. object files)
* `{worker_id}` - each worker creates a folder where it works: compile, execute and evaluate
  * `user_solution` - a sorce code file copied from user directory, for evaluation
  * `compiled_solution` - compiled app (if necessary) from source code
  * `input_data` - input data file for app
  * `output_data` - output data file from app
  * `pattern_data` - expected result

## `user` directory
user directory stores solved problems
* `{user_id}` — user solved problems by ID

## `logs` directory
Logs directory stores log data about contests, platform and other important details.
