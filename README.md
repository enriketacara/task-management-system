# Task-Management-System
1. One Time – Hourly Task
This type of task is assigned once on an hourly basis with the status "Pending."
If it is assigned for 10:30 today, then at 11:15 a reminder email will be sent to remind you to complete the task.
If by 12:15 the task is still not marked as "Completed" (it remains "Incompleted"), the system will continue to send reminder emails every hour until you mark it as "Completed."
Once the task is marked as "Completed" (e.g., at 13:15), it will be checked, and the task will be set to Inactive because it’s a One Time task.
The email reminders and status updates are handled by a cron job, which automatically runs a script at a scheduled time.

2. One Time – Daily Task
You create a daily task, and let’s say it’s currently 10:30.
If you set the completion time to 14:00, then at 14:15, if the task is not yet marked as "Completed," a reminder email will be sent.
The next day, if the task is still not completed, another reminder will be sent.
So for daily tasks, you will receive a reminder every day until the task is marked as "Completed."

Note: When assigning a daily task, if the current time is later than the set task completion time, the task will automatically be assigned for the next day, not today.
For example, if it's currently 14:00, you can't create a daily task for today with a 13:00 completion time because that time has already passed.

3. One Time – Weekly Task
When a weekly task is assigned, it is always for the current week.
For example, if today is Monday and you select Wednesday from the dropdown, the task will be set for this coming Wednesday.
On Wednesday at 10:15, the system will check if the task has been completed.
If not, a reminder email will be sent.
This email will continue to be sent every day at 10:15 until the task is marked as "Completed."
Note: For weekly tasks, the reminder time is always 10:15.

4. One Time – Monthly Task
Monthly tasks are assigned in the format date:hour:minute.
If the task is not marked as "Completed" by the specified date, a daily reminder email will be sent until it is completed.
Once it is completed, the task will be set to Inactive, but only after the assigned date has passed.

Note: If today is the 1st of the month and it's 14:00, and you create a task for 13:40, the task will be assigned for the 1st of the next month, not today.
The current time must be earlier than the task completion time you set if you're assigning the task for the same day.

1. Multi Time – Daily Task
The difference from One Time is that after the task is marked as "Completed," it does not go to the Inactive state.
Instead, it gets reassigned for the next day.

2. Multi Time – Weekly Task
Once completed, the task is reassigned for the same day of the next week.
For example, if the task was assigned for the 11th and today is the 15th of February and it gets completed, it will be reassigned for the 22nd (i.e., one week later).

3. Multi Time – Monthly Task
Once marked as "Completed," the task is automatically reassigned for the same day in the next month.

Important: When editing a task and changing its date, if the new date is later than the current date, make sure to set the status to Pending (or Completed if the task has already been completed).
![tms](https://github.com/user-attachments/assets/883a9f90-14d9-421f-8a48-6008deeaaebc)



