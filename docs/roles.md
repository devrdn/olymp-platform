# Roles and capabilities

## Roles

Available roles:

- Guest - global role
- Authenticated user - global role
- Contestant - context role in contest
- Contest manager - context role in contest
- Administrator - global role

## Capabilities

| Capability                        | GU  | AU  | CO  | CM  | AD  |
| --------------------------------- | --- | --- | --- | --- | --- |
| Login                             | +   | -   | -   | -   | -   |
| Logout                            | -   | +   | +   | +   | +   |
| View contest list                 | +   | +   | +   | +   | +   |
| View contest details              | +   | +   | +   | +   | +   |
| Create contest                    | -   | -   | -   | -   | +   |
| Edit contest                      | -   | -   | -   | +   | +   |
| Add task to contest               | -   | -   | -   | +   | +   |
| Enroll to contest                 | -   | +   | -   | -   | -   |
| Enroll another user to contest    | -   | -   | -   | +   | +   |
| Delete contest                    | -   | -   | -   | +   | +   |
| View user list                    | -   | -   | -   | +   | +   |
| View user details                 | -   | -   | -   | +   | +   |
| Create user                       | -   | -   | -   | -   | +   |
| Edit user                         | -   | -   | -   | -   | +   |
| Delete user                       | -   | -   | -   | -   | +   |
| View role list                    | -   | -   | -   | -   | +   |
| Assign role                       | -   | -   | -   | -   | +   |
| Create task in private repository | -   | -   | -   | +   | +   |
| Publish task in public repository | -   | -   | -   | +   | +   |
| View private task repository      | -   | -   | -   | +   | +   |
| View task public repo             | -   | -   | -   | +   | +   |
| View task details                 | -   | -   | +   | +   | +   |
| Edit task                         | -   | -   | -   | +   | +   |
| Delete task                       | -   | -   | -   | +   | +   |
| Submit task                       | -   | -   | +   | -   | -   |
| View submission list              | -   | -   | -   | +   | +   |
| View submission details           | -   | -   | -   | +   | +   |
| View ranking                      | +   | +   | +   | +   | +   |
