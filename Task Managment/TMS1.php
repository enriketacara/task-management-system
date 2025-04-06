<?php
#################################################################################################### --- ERRORS
error_reporting(1);
session_start();
#######################################################################################################
include_once("config/app_config.php");
if (!isset($_SESSION['login_user'])) {
    header('Location: index.php'); // Redirecting To Home Page
}
if (!isset($_SESSION['access']) || $_SESSION['access'] === 'client') {
    http_response_code(404);
    die();
}
$username=$_SESSION['useremail'];
// var_dump($username);
##################################################################ALLOW ACCESS ONLY TO CERTAIN USERS#############################################
?>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="text/javascript" charset="utf8" src="/billing-system/resources/js/popper.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/billing-system/resources/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="/billing-system/resources/css/responsive.dataTable.min.css">
    <link rel="stylesheet" type="text/css" href="/billing-system/resources/css/jquery-confirm.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="/billing-system/resources/css/jquery-ui.min.css">
    <link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="PHP/style2.css">
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

</head>
<?php include("head.php"); ?>
<?php //echo "<script>window.onload=function(){\$('[data-toggle=\"tooltip\"]').tooltip({'html': true});}</script>"; ?>

<style>

    h3 {
        margin:0 0 30px;
    }
    .ui-slider-range {
        background:green;
    }
    .percent {
        color:green;
        font-weight:bold;
        text-align:center;
        width:100%;
        border:none;
    }
    .dot {
        height: 10px;
        width: 10px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
    }
    .yellow-dot {
        background-color: yellow;
    }
    .green-dot {
        background-color: green;
    }
    .red-dot {
        background-color: red;
    }
    * {
        box-sizing: border-box;
    }

    body {
        font-family: Arial, Helvetica, sans-serif;
        margin: 0;
    }

    .column {
        float: left;
        width: 25%;
        padding: 0 10px;
    }

    .row {
        margin: 0 -5px;
    }

    .row:after {
        content: "";
        display: table;
        clear: both;
    }

    @media screen and (max-width: 600px) {
        .column {
            width: 100%;
            display: block;
            margin-bottom: 20px;
        }
    }

    .card {
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        padding: 16px;
        text-align: center;
        color: white;
        border-radius: 5px;
        margin-bottom: 20px;
        position: relative;
    }

    .card .icon {
        font-size: 4rem;
        opacity: 0.4;
        position: absolute;
        top: 20px;
        right: 20px;
    }

    .card.bg-red {
        background-color: #e74c3c;
    }

    .card.bg-yellow {
        background-color: #f1c40f;
    }

    .card.bg-green {
        background-color: #2ecc71;
    }

    .card.bg-blue {
        background-color: #3498db;
    }

    .card .link {
        color: white;
        text-decoration: underline;
        cursor: pointer;
    }
    .button-group {
        display: flex;
        gap: 10px; /* Space between buttons */
    }

    .button-group .btn {
        flex-grow: 1; /* Ensure buttons are evenly distributed */
    }

    /* CSS */
    .button-73 {
        appearance: none;
        background-color: #79e38e;
        border-radius: 40em;
        border-style: none;
        box-shadow: #348808 0 -12px 6px inset;
        box-sizing: border-box;
        color: #ffffff;
        cursor: pointer;
        display: inline-block;
        font-family: -apple-system, sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        letter-spacing: -.24px;
        margin: 0;
        outline: none;
        padding: 0.5rem 1rem;
        text-align: center;
        text-decoration: none;
        transition: all .15s;
        user-select: none;
        -webkit-user-select: none;
        touch-action: manipulation;
    }

    .button-73:hover {
        background-color: #abef85;
        box-shadow: #479b1c 0 -12px 6px inset;
        transform: scale(1.125);
    }

    .button-73:active {
        transform: scale(1.025);
    }

    /*@media (min-width: 768px) {*/
    /*    .button-73 {*/
    /*        font-size: 1.2rem; !* Adjusted font size for larger screens *!*/
    /*        padding: 0.6rem 1.2rem; !* Adjusted padding for larger screens *!*/
    /*    }*/
    /*}*/

    /* Red Circular Button */
    .button-red-circle {
        appearance: none;
        background-color: #ff0000;
        border-radius: 40em;
        border-style: none;
        box-shadow: #cc0000 0 -12px 6px inset;
        box-sizing: border-box;
        color: #ffffff;
        cursor: pointer;
        display: inline-block;
        font-family: -apple-system, sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        letter-spacing: -.24px;
        margin: 0;
        outline: none;
        padding: 0.5rem 1rem;
        text-align: center;
        text-decoration: none;
        transition: all .15s;
        user-select: none;
        -webkit-user-select: none;
        touch-action: manipulation;
    }

    .button-red-circle:hover {
        background-color: #ff4d4d;
        box-shadow: #cc0000 0 -6px 8px inset;
        transform: scale(1.125);
    }
    .button-red-circle:active {
        transform: scale(1.025);
    }

    /* Blue Rectangular Button */
    .button-blue-rect {
        appearance: none;
        background-color: #58b0e5;
        border-radius: 0.5rem;
        border-style: none;
        box-shadow: #074977 0 -6px 6px inset;
        box-sizing: border-box;
        color: #ffffff;
        cursor: pointer;
        display: inline-block;
        font-family: -apple-system, sans-serif;
        font-size: 1rem;
        font-weight: 700;
        margin: 0;
        outline: none;
        padding: 0.5rem 1rem 1rem 1rem;
        text-align: center;
        text-decoration: none;
        transition: all .15s;
        user-select: none;
        -webkit-user-select: none;
        touch-action: manipulation;
    }

    .button-blue-rect:hover {
        background-color: #5fd7f1;
        box-shadow: #08657a 0 -6px 6px inset;
        transform: scale(1.125);
    }

    .button-blue-rect:active {
        transform: scale(1.025);
    }

    /* Red Rectangular Button */
    .button-red-rect {
        appearance: none;
        background-color: #ff3131;
        border-radius: 0.5rem;
        border-style: none;
        box-shadow: #a20505 0 -6px 6px inset;
        box-sizing: border-box;
        color: #ffffff;
        cursor: pointer;
        display: inline-block;
        font-family: -apple-system, sans-serif;
        font-size: 1rem;
        font-weight: 700;
        margin: 0;
        outline: none;
        padding: 0.5rem 1rem 1rem 1rem;
        text-align: center;
        text-decoration: none;
        transition: all .15s;
        user-select: none;
        -webkit-user-select: none;
        touch-action: manipulation;
    }

    .button-red-rect:hover {
        background-color: #fc5151;
        box-shadow: #d00505 0 -6px 6px inset;
        transform: scale(1.125);
    }

    .button-red-rect:active {
        transform: scale(1.025);
    }

    /* Green Rectangular Button */
    .button-green-rect {
        appearance: none;
        background-color: #70e070;
        border-radius: 0.5rem;
        border-style: none;
        box-shadow: #089408 0 -6px 6px inset;
        box-sizing: border-box;
        color: #ffffff;
        cursor: pointer;
        display: inline-block;
        font-family: -apple-system, sans-serif;
        font-size: 1rem;
        font-weight: 700;
        margin: 0;
        outline: none;
        padding: 0.5rem 1rem 1rem 1rem;
        text-align: center;
        text-decoration: none;
        transition: all .15s;
        user-select: none;
        -webkit-user-select: none;
        touch-action: manipulation;
    }

    .button-green-rect:hover {
        background-color: #8ef68e;
        box-shadow: #067006 0 -6px 6px inset;
        transform: scale(1.125);
    }

    .button-green-rect:active {
        transform: scale(1.025);
    }
    .modal-header {
        border-bottom: none;
        border-radius: 10px 10px 0 0;
    }

    .modal-content {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }

    .modal-title i {
        margin-right: 10px;
    }

    .custom-field {
        padding: 10px 20px;
        border-top: 1px solid #f1f1f1;
    }

    .custom-field h5 {
        font-weight: bold;
        margin-bottom: 15px;
    }

    .inline-radio {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
    }

    .form-check-label {
        margin-left: 5px;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004494;
    }
    /*.project .bar {*/
    /*    position: relative;*/
    /*    width: 100%;*/
    /*    height: 20px; !* Adjust the height as needed *!*/
    /*    background-color: #f1f1f1; !* Background color of the bar *!*/
    /*}*/

    .project .percent {
        position: absolute;
        right: 0; /* Aligns the text to the right */
        top: 50%;
        transform: translateY(-50%);
        padding: 0 10px; /* Adjust padding for spacing */
        font-size: 14px; /* Adjust font size as needed */
        font-weight: bold;
        color: #333; /* Text color */
        background: none; /* No background */
        border: none; /* No border */
    }

    .view-status-history-btn {
  background: none;
  border: none;
  padding: 0;
  margin: 0;
  cursor: pointer;
}

.view-status-history-btn .glyphicon-eye-open {
  font-size: 1.2em;
  color: #000; /* Change color if needed */
}

.view-status-history-btn:hover .glyphicon-eye-open {
  color: #007bff; /* Optional: Change color on hover */
}

</style>
<!--</head>-->
<body>
<?php include("navigation.php"); ?>
<!-- Add Task Modal -->
<!-- Add Task Modal -->
<div class="modal fade" id="taskAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h4 class="modal-title" id="exampleModalLabel">
                    <i class="glyphicon glyphicon-list"></i> Add Task 
                </h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="savetask">
                <div class="modal-body">
                    <!-- Task Details Section -->
                    <div class="custom-field mb-3">
                        <h5 class="text-info"><i class="glyphicon glyphicon-info-sign"></i> TASK DETAILS</h5>
                        <div class="form-group">
                            <label for="task_title">Task Title:</label>
                            <input type="text" name="task_title" id="task_title" class="form-control" placeholder="Enter task title" required>
                        </div>
                        <div class="form-group">
                            <label for="task_des">Task Description:</label>
                            <textarea name="task_des" id="task_des" rows="3" class="form-control" placeholder="Enter task description" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="priorityDropdown">Priority</label>
                            <select class="form-control" id="priorityDropdown">
                                <option value="low">
                                    <span class="dot yellow-dot"></span> Low
                                </option>
                                <option value="medium">
                                    <span class="dot green-dot"></span> Medium
                                </option>
                                <option value="high">
                                    <span class="dot red-dot"></span> High
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="assign_task">Assign to individuals:</label>
                            <select name="assign_task[]" id="assign_task" class="form-control chosen-select" multiple>
                                <!-- Options will be populated by backend data -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="group">Assign to a group:</label>
                            <select name="group[]" id="group" class="form-control chosen-select" multiple>
                                <!-- Options will be populated by backend data -->
                            </select>
                        </div>
                    </div>

                    <!-- CRON Execution Schedule Section -->
                    <div class="custom-field mb-3">
                        <h5 class="text-success"><i class="glyphicon glyphicon-time"></i> CRON EXECUTION SCHEDULE</h5>
                        <div class="form-group">

                            <label>Select hours when CRON will be enabled. It will be enabled between below Start Time and End
                                Time:</label>
                            <div class="inline-radio">
                                <p>Start Time: </p>
                                <p style="margin-left:165px" ;>End Time: </p>
                            </div>
                            <div class="inline-radio">

                                    <select id="hourPicker_startTime" name="hourPicker_startTime" class="form-select time-picker-select">
                                        <option value="09"  selected>09</option>
                                    </select>
                                    <span>:</span>
                                    <select id="minutePicker_starTime" name="minutePicker_starTime" class="form-select time-picker-select">
                                        <option value="00"  selected>00</option>
                                    </select>


                                <select id="hourPicker_endTime" name="hourPicker_endTime" class="form-select time-picker-select" style="margin-left:10%;">
                                    <option value="18"  selected>18</option>
                                </select>
                                <span>:</span>
                                <select id="minutePicker_endTime" name="minutePicker_endTime" class="form-select time-picker-select">
                                    <option value="00"  selected>00</option>
                                </select>

                            </div>
                        </div>

                        <div class="form-group">
                            <br>
                            <div class="inline-radio">
                                <label>Select if CRON will be enabled during Weekend:</label><br>
                            </div>
                            <div class="inline-radio">
                                <input type="checkbox" id="saturday" name="saturday_cron" value="Saturday"> Saturday

                                <input type="checkbox" id="sunday" name="sunday_cron" value="Sunday"
                                       style="margin-left:40px;">Sunday
                            </div>
                        </div>
                    </div>

                    <!-- Task Frequency Section -->
                    <div class="custom-field mb-3">
                        <h5 class="text-warning"><i  class="glyphicon glyphicon-repeat"></i> TASK FREQUENCY</h5>
                        <label>Assign task: </label>
                        <div class="form-group">
                            <div class="inline-radio">
                                <input type="radio" class="time0" name="time" id="radio_once" value="One Time" />One time
                                <input type="radio" class="time1" name="time" id="radio_multi" value="Multi Time"
                                       style="margin-left:10px;" />Multi times
                            </div>

                        </div>

                        <div id="clicked_once" class="mt-2" style="display:none;">
                            <span></span>
                            <div class="inline-radio">
                                    <input type="radio" class="form-check-input" value="Hourly" id="radio1" name="radio">Hourly
                                    <input type="radio" class="form-check-input" value="Daily" id="radio2" name="radio">Daily
                                    <input type="radio" class="form-check-input" value="Weekly" id="radio3" name="radio">Weekly
                                    <input type="radio" class="form-check-input" value="Monthly" id="radio4" name="radio">Monthly

                            </div>
                        </div>

                        <div id="clicked_multi" class="mt-2" style="display:none;">
                            <span></span>
                            <div class="inline-radio">
                                    <input type="radio" class="form-check-input" value="Daily" id="radio5" name="radio">Daily
                                    <input type="radio" class="form-check-input" value="Weekly" id="radio6" name="radio">Weekly
                                    <input type="radio" class="form-check-input" value="Monthly" id="radio7" name="radio">Monthly
                            </div>
                        </div>



                        <div id="taskFrequencyDetails" class="task-container" style="display: none;">
                            <div id="hourlyContainer" style="display: none;">
                                <label></label>
                                <div class="inline-radio">
                                    <select id="hourly_hourPicker" class="time-picker-select">
                                        <option value="" disabled selected>hh</option>
                                    </select>
                                    <span>:</span>
                                    <select id="hourly_minutePicker" class="time-picker-select">
                                        <option value="" disabled selected>mm</option>
                                    </select>
                                </div>
                            </div>

                            <div id="dailyContainer" style="display: none;">
                                <label></label>
                                <div class="inline-radio">
                                    <select id="daily_hourPicker" class="time-picker-select">
                                        <option value="" disabled selected>hh</option>
                                    </select>
                                    <span>:</span>
                                    <select id="daily_minutePicker" class="time-picker-select">
                                        <option value="" disabled selected>mm</option>
                                    </select>
                                </div>
                            </div>

                            <div id="weeklyContainer" style="display: none;">
                                <label></label>
                                <select id="weekly_dayPicker" class="form-select time-picker-select">
                                <option value="" disabled selected>Select a weekday </option>
                                    <option value="Monday">Monday</option>
                                    <option value="Tuesday">Tuesday</option>
                                    <option value="Wednesday">Wednesday</option>
                                    <option value="Thursday">Thursday</option>
                                    <option value="Friday">Friday</option>
                                    <option value="Saturday">Saturday</option>
                                    <option value="Sunday">Sunday</option>
                                </select>
                            </div>

                            <div id="monthlyContainer" style="display: none;">
                                <label></label>
                                <div class="inline-radio">
                                    <select id="monthly_dayPicker" class="time-picker-select">
                                        <option value="" disabled selected>dd</option>
                                    </select>
                                    <span>:</span>
                                    <select id="monthly_hourPicker" class="time-picker-select">
                                        <option value="" disabled selected>hh</option>
                                    </select>
                                    <span>:</span>
                                    <select id="monthly_minutePicker" class="time-picker-select">
                                        <option value="" disabled selected>mm</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class=" btn-primary block" id="save_task_button_"><i class="	glyphicon glyphicon-save"></i> Save task</button>


                </div>
                    <div id="errorMessage" class="alert alert-warning d-none"></div>
                </div>
<!--                <div class="modal-footer">-->
<!--                </div>-->
            </form>
    </div>
</div>

<!--Edit modal-->
<div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h4 class="modal-title" id="exampleModalLabel">
                    <i class="glyphicon glyphicon-edit"></i> Edit Task
                </h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editTaskForm">
                <div class="modal-body">
                    <input type="hidden" name="task_id" id="task_id">
                    <!-- Task Details Section -->
                    <div class="custom-field mb-3">
                        <h5 class="text-info"><i class="glyphicon glyphicon-info-sign"></i> TASK DETAILS</h5>
                        <div class="form-group">
                            <label for="task_title">Task Title:</label>
                            <input type="text" name="edit_task_title" id="edit_task_title" class="form-control" placeholder="Enter task title" required>
                        </div>
                        <div class="form-group">
                            <label for="task_des">Task Description:</label>
                            <textarea name="edit_task_des" id="edit_task_des" rows="3" class="form-control" placeholder="Enter task description" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="priorityDropdown">Priority</label>
                            <select class="form-control" id="edit_priorityDropdown" name="edit_priority">
                                <option value="low">
                                    <span class="dot yellow-dot"></span> Low
                                </option>
                                <option value="medium">
                                    <span class="dot green-dot"></span> Medium
                                </option>
                                <option value="high">
                                    <span class="dot red-dot"></span> High
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                                    <div class="project ">
                                        <label for="progress">Progress</label>
                                        <div class="bar"> <input type="text" class="percent" id="edit_progress" name="edit_progress" readonly /></div>
                                    </div>
                        </div>
                        <div class="form-group">
                            <label for="edit_status">Select status:</label>

                            <select id="edit_status" name="edit_status" class="form-control ">
                                <option value="" disabled selected>Select status </option>
                                <option value="Pending">Pending</option>
                                <option value="Incompleted">Incompleted</option>
                                <option value="Completed">Completed</option>

                            </select>
                        </div>
                        <div class="form-group">
                            <label for="assign_task">Assign to individuals:</label>
                            <select name="edit_assign_task[]" id="edit_assign_task" class="form-control chosen-select" multiple>
                                <!-- Options will be populated by backend data -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="group">Assign to a group:</label>
                            <select name="edit_group[]" id="edit_group" class="form-control chosen-select" multiple>
                                <!-- Options will be populated by backend data -->
                            </select>
                        </div>
                    </div>

                    <!-- CRON Execution Schedule Section -->
                    <div class="custom-field mb-3">
                        <h5 class="text-success"><i class="glyphicon glyphicon-time"></i> CRON EXECUTION SCHEDULE</h5>
                        <div class="form-group">

                            <label>Select hours when CRON will be enabled. It will be enabled between below Start Time and End
                                Time:</label>
                            <div class="inline-radio">
                                <p>Start Time: </p>
                                <p style="margin-left:165px" ;>End Time: </p>
                            </div>
                            <div class="inline-radio">

                                <select id="edit_hourPicker_startTime" name="edit_hourPicker_startTime"  class="form-select time-picker-select">
                                    <option value="" disabled selected>hh</option>
                                </select>
                                <span>:</span>
                                <select id="edit_minutePicker_starTime" name="edit_minutePicker_starTime"  class="form-select time-picker-select">
                                    <option value="" disabled selected>mm</option>
                                </select>


                                <select id="edit_hourPicker_endTime" name="edit_hourPicker_endTime" class="form-select time-picker-select" style="margin-left:10%;">
                                    <option value="" disabled selected>hh</option>
                                </select>
                                <span>:</span>
                                <select id="edit_minutePicker_endTime" name="edit_minutePicker_endTime" class="form-select time-picker-select">
                                    <option value="" disabled selected>mm</option>
                                </select>

                            </div>
                        </div>

                        <div class="form-group">
                            <br>
                            <div class="inline-radio">
                                <label>Select if CRON will be enabled during Weekend:</label><br>
                            </div>
                            <div class="inline-radio">
                                <input type="checkbox" id="edit_saturday" name="edit_saturday_cron" value="Saturday"> Saturday

                                <input type="checkbox" id="edit_sunday" name="edit_sunday_cron" value="Sunday"
                                       style="margin-left:40px;">Sunday
                            </div>
                        </div>
                    </div>

                    <!-- Task Frequency Section -->
                    <div class="custom-field mb-3">
                        <h5 class="text-warning"><i  class="glyphicon glyphicon-repeat"></i> TASK FREQUENCY</h5>
                        <label>Assign task: </label>
                        <div class="form-group">
                            <div class="inline-radio">
                                <input type="radio" class="time0" name="edit_time" id="edit_radio_once" value="One Time" />One time
                                <input type="radio" class="time1" name="edit_time" id="edit_radio_multi" value="Multi Time"
                                       style="margin-left:10px;" />Multi times
                            </div>

                        </div>

                        <div id="edit_clicked_once" class="mt-2" style="display:none;">
                            <span></span>
                            <div class="inline-radio">
                                <input type="radio" class="form-check-input" value="Hourly" id="edit_radio1" name="edit_radio">Hourly
                                <input type="radio" class="form-check-input" value="Daily" id="edit_radio2" name="edit_radio">Daily
                                <input type="radio" class="form-check-input" value="Weekly" id="edit_radio3" name="edit_radio">Weekly
                                <input type="radio" class="form-check-input" value="Monthly" id="edit_radio4" name="edit_radio">Monthly

                            </div>
                        </div>

                        <div id="edit_clicked_multi" class="mt-2" style="display:none;">
                            <span></span>
                            <div class="inline-radio">
                                <input type="radio" class="form-check-input" value="Daily" id="edit_radio5" name="edit_radio">Daily
                                <input type="radio" class="form-check-input" value="Weekly" id="edit_radio6" name="edit_radio">Weekly
                                <input type="radio" class="form-check-input" value="Monthly" id="edit_radio7" name="edit_radio">Monthly
                            </div>
                        </div>



                        <div id="edit_taskFrequencyDetails" class="task-container" style="display: none;">
                            <div id="edit_hourlyContainer" style="display: none;">
                                <label></label>
                                <div class="inline-radio">
                                    <select id="edit_hourly_hourPicker" class="time-picker-select">
                                        <option value="" disabled selected>hh</option>
                                    </select>
                                    <span>:</span>
                                    <select id="edit_hourly_minutePicker" class="time-picker-select">
                                        <option value="" disabled selected>mm</option>
                                    </select>
                                </div>
                            </div>

                            <div id="edit_dailyContainer" style="display: none;">
                                <label></label>
                                <div class="inline-radio">
                                    <select id="edit_daily_hourPicker" class="time-picker-select">
                                        <option value="" disabled selected>hh</option>
                                    </select>
                                    <span>:</span>
                                    <select id="edit_daily_minutePicker" class="time-picker-select">
                                        <option value="" disabled selected>mm</option>
                                    </select>
                                </div>
                            </div>

                            <div id="edit_weeklyContainer" style="display: none;">
                                <label></label>
                                <select id="edit_weekly_dayPicker" class="form-select time-picker-select">
                                <option value="" disabled selected>Select a weekday </option>
                                    <option value="Monday">Monday</option>
                                    <option value="Tuesday">Tuesday</option>
                                    <option value="Wednesday">Wednesday</option>
                                    <option value="Thursday">Thursday</option>
                                    <option value="Friday">Friday</option>
                                    <option value="Saturday">Saturday</option>
                                    <option value="Sunday">Sunday</option>
                                </select>
                            </div>

                            <div id="edit_monthlyContainer" style="display: none;">
                                <label></label>
                                <div class="inline-radio">
                                    <select id="edit_monthly_dayPicker" class="time-picker-select">
                                        <option value="" disabled selected>dd</option>
                                    </select>
                                    <span>:</span>
                                    <select id="edit_monthly_hourPicker" class="time-picker-select">
                                        <option value="" disabled selected>hh</option>
                                    </select>
                                    <span>:</span>
                                    <select id="edit_monthly_minutePicker" class="time-picker-select">
                                        <option value="" disabled selected>mm</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class=" btn-primary block"><i class="	glyphicon glyphicon-save"></i> Edit task</button>


                </div>
                <div id="errorMessage" class="alert alert-warning d-none"></div>
        </div>
        <!--                <div class="modal-footer">-->
        <!--                </div>-->
        </form>
    </div>
</div>
<div class="modal fade" id="viewinfo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="exampleModalLabel">Tms Info</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <form id="viewinfo">
            <div class="modal-body">
            

                <div class="custom-field">
                  <label>TMS infos: </label>
                <p>Cron is executed every <strong>hh:15</strong></p> 
                <p style="font-size:15px;"><br>
                    <strong> 1.One Time-Hourly Task</strong>
                    Behet assign tasku hourly one time me status pending dhe nqs i bejme assign per ne oren
                    10:30 te dites se sotme ,ne oren 11:15 do te vije emaili reminder per tju kujtuar qe ta perfundoni
                    kete task,nqs ne oren 12:15 vazhdoni te mos
                    keni bere statusin e taskut Completed nga Incompleted qe eshte tashme, atehere do te vazhdoje te vije email
                    perseri cdo ore derisa ta beni Completed dhe ne momentin qe do behet Completed psh ne oren 13:15 do
                    te kontrollohet dhe do te behet task Inactiv per shkak se eshte One Time.
                    Emaili dhe fshirja behen te mundura nepermjet cronit qe automatikisht ne nje kohe te caktuar
                    ekzekuton nje file .
                   
                    <br>
                    <strong>2.One Time-Daily Task</strong>
                    Krijojme nje task ditor dhe momentalisht eshte ora 10:30,dhe oren kur duam qe tasku te jete i
                    perfunduar e bejme 14:00 dhe ne oren 14:15 nqs nuk eshte bere Completed tasku atehere do te vije nje
                    email i cili ju kujton per kete task qe ju ta perfundoni,dhe perseri diten tjeter nqs nuk eshte bere
                    tasku Completed do te vije nje email,pra per taksun ditor ju do te kujtoheni cdo dite derisa te
                    behet statusi Completed.
                    <strong>Kujdes:</strong>Kur perpiqeni te beni assign task ditor dhe ora aktuale eshte me e madhe se
                    koha qe keni vene per taskun qe te jete i perfunduar,ky task behet assign per diten tjeter
                    automatikisht dhe jo per diten e sotme.
                    Nqs eshte ora 14:00 tani ,s'mund te bej nje task ditor per sot ne oren 13:00 pasi ka kaluar si dite.
                    <br><strong>3.One Time-Weekly Task</strong>
                    Tasku javor kur behet assign behet gjithmone per kete dite jave,psh nqs sot eshte e hene dhe
                    vendosni selektoni diten e merkure tek dropdowni atehere do te behet fjale per kte te merkure.
                    Kur te vije e merkura ora 10:15 do te behet kontolli nqs eshte bere Completed tasku dhe nqs tasku
                    nuk eshte bere Completed ,atehere do te dergohet nje email reminder.Ky email do te vazhdoje te
                    dergohet cdo dite derisa te behet tasku Completed.
                   
                    Ora 10:15 eshte e percaktuar si orar ku do te vije emaili cdo dite nqs tasku nuk eshte bere
                    Completed ne diten e caktuar.
                    Vetem per tasket javore eshte i percaktuar ky orar.
                    <br><strong>4.One Time- Monthly Time</strong>
                    Tasku mujor behet assign ne formatin date:hour:minute.
                    Nqs tasku nuk behet Completed ne daten e percaktuar atehere do te dergohet cdo dite email per tju
                    rikujtuar dhe kur te behet Completed Tasku do te kaloje ne status Inactive.Tasku nuk behet Inactive menjehere pasi behet
                    Completed,tasku fshihet ne momentin qe ka ardhur data e percaktuar ose ka kaluar .
                    <strong>Kujdes:</strong>Nqs eshte data 01 ora 14:00 dhe krijoni nje task per oren 13:40 ky task do
                    te behet assign per daten 01 te muajit tjeter jo per diten e sotme.Pasi koha aktuale duhet te jete
                    me e vogel se koha per te cilen doni qe tasku te perfundohet.
                    Ky rast behet fjale kur jemi brenda dites ,pra data eshte 01 dhe beni assign task mujor per daten
                    01.
                    <br>
                    <strong>1.Multi Time-Daily Task</strong>
                   Ndryshimi nga One time qendron tek fakti se pasi tasku behet Completed tasku 
                   nuk kalon ne gjendje Inactive por behet riassign per diten tjeter.
                    <br><strong>2.Multi Time-Weekly Task</strong>
                    Pasi behet Completed tasku atehere do te behet re assign per javen tjeter.Psh tasku ka qene bere assign per daten 11 dhe sot 
                     eshte data 15 shkurt dhe behet completed ,atehere 
                    tasku do te behet reassign per pas nje jave qe i bie data 22(15 deri 22 = 1jave)
                    
                    <br><strong>3.Multi Time- Monthly Time</strong>
                    Kur behet Completed behet assign automatikisht perseri per muajin tjeter.
                </p>
                <strong>Kujdes: Kur te beni editimin e nje tasku,dhe ndryshoni daten,dhe
                     data eshte me e madhe se data aktuale bejeni statusin Pending (ose Completed nqs taskun e keni mbaruar). </strong>
            </form>
                     </div>
                     </div>
                     </div>
                     </div>
                     </div>
                     

<!-- end of info modal -->

<!--View modal-->
<div class="modal fade" id="viewTaskModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title " id="viewTaskModalLabel">View Task Details</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered "  style="width: 95%;">
                    <tbody>
                    <tr>
                        <th>ID</th>
                        <td id="view_task_id"></td>
                    </tr>
                    <tr>
                        <th>Task Title</th>
                        <td id="view_task_title"></td>
                    </tr>
                    <tr>
                        <th>Task Description</th>
                        <td id="view_task_description"></td>
                    </tr>
                    <tr>
                        <th>Frequency</th>
                        <td id="view_frequency"></td>
                    </tr>
                    <tr>
                        <th>Task Type</th>
                        <td id="view_task_type"></td>
                    </tr>
                    <tr>
                        <th>Task Time</th>
                        <td id="view_task_time"></td>
                    </tr>
                    <tr>
                        <th>Progress</th>
                        <td id="view_progress"></td>
                    </tr>
                    <tr>
                        <th>Created By</th>
                        <td id="view_created_by"></td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td id="view_created_at"></td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td id="view_updated_at"></td>
                    </tr>
                    <tr>
                        <th>Cron Start Time</th>
                        <td id="view_cron_start_time"></td>
                    </tr>
                    <tr>
                        <th>Cron End Time</th>
                        <td id="view_cron_end_time"></td>
                    </tr>
                    <tr>
                        <th>Saturday</th>
                        <td id="view_saturday"></td>
                    </tr>
                    <tr>
                        <th>Sunday</th>
                        <td id="view_sunday"></td>
                    </tr>
                    <tr>
                        <th>Priority</th>
                        <td id="view_priority"></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- status history modal -->
<div class="modal fade" id="statusHistoryModal" tabindex="-1" role="dialog" aria-labelledby="statusHistoryModalLabel" >
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusHistoryModalLabel">Status History</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered" id="statusHistoryTable" style="width: 95%;">
                <thead>
        <tr>
            <th>From</th>
            <th>To</th>
            <th>By</th>
            <th>At</th>
        </tr>
        <tr>
            <!-- Input fields for searching each column -->
            <th><input type="text" placeholder="Search "></th>
            <th><input type="text" placeholder="Search "></th>
            <th><input type="text" placeholder="Search "></th>
            <th><input type="text" placeholder="Search "></th>
        </tr>
    </thead>
    <tbody>
        <!-- Rows will be dynamically inserted here by DataTables or directly in HTML -->
    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!--end of edit modal-->
<button type="button"   class="btn btn-primary" data-toggle="modal" data-target="#taskAddModal" style="background-color: #17B169; color:black;">
    <span class="glyphicon glyphicon-plus"></span>  Add task
</button> 
<!-- <span data-toggle="tooltip"  title="Here will be uploaded info how to use TMS.">▌</span> -->
<button type="button"   class="btn btn-primary" data-toggle="modal" data-target="#viewinfo" style="background-color: #17B169; color:black;">
                    <span class="glyphicon glyphicon-info-sign"></span>
                        </button>
<label for="taskFilter"></label>
<select id="taskFilter" class="form-select">
    <option value="active" selected>Show Active</option>
    <option value="inactive">Show Inactive</option>
    <option value="all">Show All</option>
</select>


<div class="row " >
    <div class="column">
        <div class="card bg-blue">
            <i class="glyphicon glyphicon-plus icon"></i>
            <h3 class="card-title">High Priority & Incomplete Tasks</h3>
            <p class="card-text">Total: <span id="highPriorityIncompleteTasksCard">0</span></p>
            <!-- <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#myModal">View Details</a> -->
<!--            <p class="link" data-toggle="modal" data-target="#myModal">More details</p>-->
        </div>
    </div>

    <div class="column">
        <div class="card bg-yellow">
            <i class="glyphicon glyphicon-list icon"></i>
            <h3>Incomplete Tasks</h3>
            <p>Total: <span id="incompleteTasksCard">0</span></p>
        </div>
    </div>

    <div class="column">
        <div class="card bg-green">
            <i class="	glyphicon glyphicon-time icon"></i>
            <h3>Pending Tasks</h3>
            <p>Total: <span id="pendingTasksCard">0</span></p>
        </div>
    </div>

    <div class="column">
        <div class="card bg-red">
            <i class="		glyphicon glyphicon-exclamation-sign icon"></i>
            <h3>High Priority Tasks</h3>
            <p>Total: <span id="highPriorityTasksCard">0</span></p>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Card 1 Details</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Here are more details about Card 1...</p>
                <canvas id="taskChart"></canvas>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>




<table id="task_section">
    <thead>
    <tr>
        <th>ID</th>
        <th>Task Title</th>
        <th >Task Description</th>
        <th>Assigned to</th>
        <th>Task Type</th>

        <th>Task Time</th>
        <th>Status</th>
        <th>Progress</th>

        <th>Active</th>
        <th>Comment</th>
        <th>Status history</th>
<!--        <th>Updated At</th>-->
<!--        <th>Created At</th>-->
        <th>Created By</th>
        <th>Overdue</th>
        <th style="max-width: 100px !important">Actions</th>
        <tr>
        <th>ID</th>
        <th>Task Title</th>
        <th >Task Description</th>
        <th>Assigned to</th>
        <th>Task Type</th>

        <th>Task Time</th>
        <th>Status</th>
        <th>Progress</th>

        <th>Active</th>
        <th>Comment</th>
       <th>Status history</th>
<!--        <th>Created At</th>-->
        <th>Created By</th>
        <th>Overdue</th>

        <th style="max-width: 100px !important">Actions</th>
    </tr>
    </tr>
    </thead>
<tbody>

</tbody>
    <tfoot>

    </tfoot>
</table>
<title>Task Management</title>


<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<!-- DataTables Responsive JS -->
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<!-- #################################################################################################### --- FOOTER -->
<script type="text/javascript" charset="utf8" src="/billing-system/resources/js/alertify.min.js"></script>
<script type="text/javascript" charset="utf8" src="/billing-system/resources/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="/billing-system/resources/js/jquery-confirm.min.js"></script>
<script type="text/javascript" charset="utf8" src="/billing-system/resources/js/jquery-ui.min.js"></script>
<script type="text/javascript" charset="utf8" src="/billing-system/resources/js/decimal.min.js"></script>
<script type="text/javascript" charset="utf8" src="/billing-system/resources/js/loadingoverlay.min.js"></script>
<script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- <script type="text/javascript" src="/billing-system/resources/js/services/payseraVSbankreportServices.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>


    // $(document).ready(function() {
    //     // Fetch task summary when the page loads
    //     $.ajax({
    //         url: './api/v1/TMS/get_hight_priority_incomplete_tasks.php',
    //         type: 'GET',
    //         dataType: 'json',
    //         success: function(response) {
    //             if (response.success) {
    //                 // Display the total number of tasks on the card
    //                 $('#totalTasksCard').text(response.total_tasks);
    //
    //                 // Prepare data for the chart
    //                 var chartLabels = [];
    //                 var chartData = [];
    //                 response.task_summary.forEach(function(userTask) {
    //                     // Only include users with incomplete high-priority tasks
    //                     if (userTask.task_count > 0) {
    //                         chartLabels.push(userTask.firstname + ' ' + userTask.lastname);
    //                         chartData.push(userTask.task_count);
    //                     }
    //                 });
    //
    //                 // Draw the horizontal bar chart in the modal
    //                 var ctx = document.getElementById('taskChart').getContext('2d');
    //                 var config = {
    //                     type: 'bar',
    //                     data: {
    //                         labels: chartLabels,
    //                         datasets: [{
    //                             label: 'High Priority & Incomplete Tasks',
    //                             data: chartData,
    //                             backgroundColor: 'rgba(54, 162, 235, 0.2)',
    //                             borderColor: 'rgba(54, 162, 235, 1)',
    //                             borderWidth: 1
    //                         }]
    //                     },
    //                     options: {
    //                         indexAxis: 'y', // Create a horizontal bar chart
    //                         plugins: {
    //                             legend: {
    //                                 display: false // Hide the legend
    //                             },
    //                             title: {
    //                                 display: true,
    //                                 text: 'High Priority & Incomplete Tasks by User' // Chart title
    //                             }
    //                         },
    //                         scales: {
    //                             x: {
    //                                 beginAtZero: true, // Ensure the x-axis starts at 0
    //                                 ticks: {
    //                                     callback: function(value, index, values) {
    //                                         return Number.isInteger(value) ? value : ''; // Ensure only whole numbers are displayed
    //                                     }
    //                                 }
    //                             },
    //                             y: {
    //                                 ticks: {
    //                                     stepSize: 1, // Ensure whole numbers on the y-axis
    //                                     callback: function(value, index, values) {
    //                                         return index; // Show incremental numbers starting from 0
    //                                     }
    //                                 }
    //                             }
    //                         }
    //                     }
    //                 };
    //                 new Chart(ctx, config);
    //             } else {
    //                 console.log(response.message);
    //             }
    //         },
    //         error: function(xhr, status, error) {
    //             console.log('AJAX error: ' + error);
    //         }
    //     });
    //
    //     // Show the modal with the graph
    //     $('#myModal').on('shown.bs.modal', function() {
    //         $('#taskChart').show();
    //     });
    // });



    document.addEventListener("DOMContentLoaded", function() {
        // Show clicked_once or clicked_multi based on radio button selection
        document.querySelectorAll('input[name="time"]').forEach(function(elem) {
            elem.addEventListener('change', function() {
                // Hide all time detail containers and reset task types
                document.getElementById('taskFrequencyDetails').style.display = 'none';
                document.querySelectorAll('#taskFrequencyDetails > div').forEach(function(container) {
                    container.style.display = 'none';
                });

                // Show the selected option (One time or Multi time)
                if (elem.id === 'radio_once') {
                    document.getElementById('clicked_once').style.display = 'block';
                    document.getElementById('clicked_multi').style.display = 'none';
                } else if (elem.id === 'radio_multi') {
                    document.getElementById('clicked_once').style.display = 'none';
                    document.getElementById('clicked_multi').style.display = 'block';
                }
            });
        });

        // Show task details based on selected frequency
        document.querySelectorAll('input[name="radio"]').forEach(function(elem) {
            elem.addEventListener('change', function() {
                document.getElementById('taskFrequencyDetails').style.display = 'block';
                document.querySelectorAll('#taskFrequencyDetails > div').forEach(function(container) {
                    container.style.display = 'none';
                });
                if (elem.value === 'Hourly') {
                    document.getElementById('hourlyContainer').style.display = 'block';
                } else if (elem.value === 'Daily') {
                    document.getElementById('dailyContainer').style.display = 'block';
                } else if (elem.value === 'Weekly') {
                    document.getElementById('weeklyContainer').style.display = 'block';
                } else if (elem.value === 'Monthly') {
                    document.getElementById('monthlyContainer').style.display = 'block';
                }
            });
        });



            // Show clicked_once or clicked_multi based on radio button selection
            document.querySelectorAll('input[name="edit_time"]').forEach(function(elem) {
                elem.addEventListener('change', function() {
                    // Hide all time detail containers and reset task types
                    document.getElementById('edit_taskFrequencyDetails').style.display = 'none';
                    document.querySelectorAll('#edit_taskFrequencyDetails > div').forEach(function(container) {
                        container.style.display = 'none';
                    });

                    // Show the selected option (One time or Multi time)
                    if (elem.id === 'edit_radio_once') {
                        document.getElementById('edit_clicked_once').style.display = 'block';
                        document.getElementById('edit_clicked_multi').style.display = 'none';
                    } else if (elem.id === 'edit_radio_multi') {
                        document.getElementById('edit_clicked_once').style.display = 'none';
                        document.getElementById('edit_clicked_multi').style.display = 'block';
                    }
                });
            });

            // Show task details based on selected frequency
            document.querySelectorAll('input[name="edit_radio"]').forEach(function(elem) {
                elem.addEventListener('change', function() {
                    document.getElementById('edit_taskFrequencyDetails').style.display = 'block';
                    document.querySelectorAll('#edit_taskFrequencyDetails > div').forEach(function(container) {
                        container.style.display = 'none';
                    });
                    if (elem.value === 'Hourly') {
                        document.getElementById('edit_hourlyContainer').style.display = 'block';
                    } else if (elem.value === 'Daily') {
                        document.getElementById('edit_dailyContainer').style.display = 'block';
                    } else if (elem.value === 'Weekly') {
                        document.getElementById('edit_weeklyContainer').style.display = 'block';
                    } else if (elem.value === 'Monthly') {
                        document.getElementById('edit_monthlyContainer').style.display = 'block';
                    }
                });
            });

        // Populate hours (00 to 23) and minutes (00 to 59) for all task types
        populateTimeSelectors('hourly_hourPicker', 'hourly_minutePicker');
        populateTimeSelectors('daily_hourPicker', 'daily_minutePicker');
        populateTimeSelectors('monthly_hourPicker', 'monthly_minutePicker');
        populateTimeSelectors('hourPicker_startTime', 'minutePicker_starTime');
        populateTimeSelectors('hourPicker_endTime', 'minutePicker_endTime');

        populateTimeSelectors('edit_hourly_hourPicker', 'edit_hourly_minutePicker');
        populateTimeSelectors('edit_daily_hourPicker', 'edit_daily_minutePicker');
        populateTimeSelectors('edit_monthly_hourPicker', 'edit_monthly_minutePicker');
        populateDaySelectors('edit_monthly_dayPicker');
        populateTimeSelectors('edit_hourPicker_startTime', 'edit_minutePicker_starTime');
        populateTimeSelectors('edit_hourPicker_endTime', 'edit_minutePicker_endTime');
        // Populate days (01 to 31) for monthly tasks
        for (let i = 1; i <= 31; i++) {
            let day = i < 10 ? '0' + i : i;
            document.getElementById('monthly_dayPicker').innerHTML += `<option value="${day}">${day}</option>`;
        }
    });

    function populateTimeSelectors(hourPickerId, minutePickerId) {
        // Populate hours (00 to 23)
        for (let i = 0; i < 24; i++) {
            let hour = i < 10 ? '0' + i : i;
            document.getElementById(hourPickerId).innerHTML += `<option value="${hour}">${hour}</option>`;
        }
        // Populate minutes (00 to 59)
        for (let i = 0; i < 60; i++) {
            let minute = i < 10 ? '0' + i : i;
            document.getElementById(minutePickerId).innerHTML += `<option value="${minute}">${minute}</option>`;
        }
    }
    function populateDaySelectors(dayPickerId) {
        // Populate hours (00 to 23)
        for (let i = 0; i < 31; i++) {
            let day = i < 10 ? '0' + i : i;
            document.getElementById(dayPickerId).innerHTML += `<option value="${day}">${day}</option>`;
        }
        
    }

    $(document).ready(function() {

//     $('#taskFilter').on('change', function() {
//         var filterValue = $(this).val();
//         console.log('Filter value selected:', filterValue);

//         if (filterValue === 'active') {
//     console.log('Filtering for active tasks...');
//     table.column(8).search('^Active$', true, false).draw();  // Case-insensitive search for "Active"
// } else if (filterValue === 'inactive') {
//     console.log('Filtering for inactive tasks...');
//     table.column(8).search('^Inactive$', true, false).draw();  // Case-insensitive search for "Inactive"
// } else {
//     console.log('Showing all tasks...');
//     table.column(8).search('').draw();  // Clear search filter
// }
//     });

        populateDropdowns();
        $('#assign_task').chosen();
        $('#group').chosen();
         // Assuming the email is stored in the session
// console.log(userEmail);
        var table = $('#task_section').DataTable({
            "processing": true,
            "responsive": true,
            "serverSide": false,
            "paging": true,
            "pageLength": 10,
            "ajax": './api/v1/TMS/get_tasks.php',
            "columns": [
                {
                    "data": null,
                    "render": function(data, type, row) {
                        let priority = row.priority; // Assuming 'priority' is the column name in your database
                        let dotColor;

                        if (priority === 'high') {
                            dotColor = 'red';
                        } else if (priority === 'medium') {
                            dotColor = 'green';
                        } else if (priority === 'low') {
                            dotColor = 'yellow';
                        }

                        return '<span style="display: inline-block; width: 10px; height: 10px; background-color: ' + dotColor + '; border-radius: 50%; margin-right: 5px;"></span>' +
                            row.id;
                    }
                },

                { "data": "task_title" },
                {
                    "data": "task_description",
                    "render": function(data, type, row) {
                        return '<div style="max-width: 200px; max-height: 100px; overflow: auto; white-space: normal; word-wrap: break-word;" title="' + data + '">' + data + '</div>';
                    }
                },

                {
                    "data": null,
                    "render": function(data, type, row) {
                        var users = row.users.join(', ');
                        var groups = row.groups.join(', ');

                        return '' + users + '<br>' +
                            ' ' + groups;
                    }
                },

                {
                    data: null,
                    render: function(data, type, row) {
                        return data.frequency + ' ' + data.task_type;
                    }
                },
                { "data": "task_time" },
                {
                    "data": "status",
                    "render": function (data, type, row) {
                        let statusText = '';
                        let buttonText = '';
                        let buttonClass = '';

                        switch (data) {
                            case 'Completed':
                                statusText = '<span style="color: green;" margin-left:10px; class="glyphicon glyphicon-ok-circle">Completed</span><br>';
                                buttonText = 'Incomplete';
                                buttonClass = 'button-red-circle mark-incomplete-btn';
                                break;
                            case 'Incompleted':
                                statusText = '<span style="color: red;" class="glyphicon glyphicon-remove-circle">Incompleted</span><br>';
                                buttonText = 'Complete';
                                buttonClass = 'button-73 mark-complete-btn';
                                break;
                            case 'Pending':
                                statusText = '<span style="color: #e7c10a;" class="glyphicon glyphicon-hourglass">Pending...</span><br>';
                                buttonText = 'Complete';
                                buttonClass = 'button-73 mark-complete-btn';
                                break;
                        }

                        return statusText + '<br>' +
                            '<button class="btn ' + buttonClass + '" data-id="' + row.id + '">' + buttonText + '</button>';
                    }
                },
                {
                    "data": "progress",
                    "render": function(data, type, row) {
                        let progress = data;
                        let color;

                        if (progress < 25) {
                            color = '#ff0000'; // Red
                        } else if (progress < 50) {
                            color = '#ffa500'; // Orange
                        } else if (progress < 75) {
                            color = '#c7c710'; // Yellow
                        } else if (progress < 90) {
                            color = '#97b91e'; // Greenish
                        } else {
                            color = '#0c930c'; // Green
                        }

                        return '<div class="progress" style="width: 100%; background-color: #e9ecef;">' +
                            '<div class="progress-bar" role="progressbar" style="width: ' + progress + '%; background-color: ' + color + ';" aria-valuenow="' + progress + '" aria-valuemin="0" aria-valuemax="100">' +
                            progress + '%' +
                            '</div>' +
                            '</div>';
                    }
                },
                {
                    "data": "active",
                    "render": function(data, type, row) {
                        let buttonText = data == 1 ? 'Active' : 'Inactive';
                        let buttonClass = data == 1 ? 'button-green-rect' : 'button-red-rect';
                        let tooltipText = data == 1 ? 'Click to deactivate' : 'Click to activate';

                        return '<div>' +
                            '<button class="btn ' + buttonClass + ' toggle-active-btn" data-id="' + row.id + '" title="' + tooltipText + '">' +
                            buttonText +
                            '</button>' +
                            '</div>';
                    }
                },

                // { "data": "comment" },
                {
                    "data": null,
                    "render": function (data, type, row) {
                        return '<div>' +
                            '<div style="max-height: 150px; overflow: auto; ">' +
                            '<p>' + (row.comment ? row.comment : 'No comments available') + '</p>' +
                            '</div>' +
                            '<button class="button-blue-rect toggle-comment-btn" data-id="' + row.id + '" data-toggle="collapse" data-target="#collapseComment' + row.id + '"><span class="glyphicon glyphicon-plus"></span></button> ' +
                             '<button class="button-red-rect delete-comment-btn" data-id="' + row.id + '"><span class="glyphicon glyphicon-trash"></span></button>'+
                            '<div id="collapseComment' + row.id + '" class="collapse">' +
                            '<br><textarea class="form-control comment-textarea" rows="2" placeholder="Enter your comment here..."></textarea>' +
                            '<div class="button-group" style="margin-top: 5px;">' +
                            '<button class="btn btn-primary submit-comment-btn" data-id="' + row.id + '"> <span class="glyphicon glyphicon-plus"></span>Add </button>'  +
                            '</div>' +
                            '</div>' +
                            '</div>';
                    }
                },
                
                {
               "data": null,
               "render": function(data, type, row) {
               // Check if status_last_updated is null or empty
               var statusLastUpdated = row.status_last_updated ? row.status_last_updated : ' ';
    
                 return statusLastUpdated + 
                ' <button class="view-status-history-btn" data-id="' + row.id + '" data-status-history="' + row.status_updated_by + '">' +
                '<span class="glyphicon glyphicon-eye-open"></span> </button>';
                                                 }
                },

                // { "data": "created_at" },
                { "data": "created_by" },
                {
    "data": null,
    "render": function(data, type, row) {
        // Get the current time and task time
        var currentTime = new Date();
        var taskTime = new Date(row.task_time); // Ensure task_time is in a valid date format

        var overdueTime = '';

        // Check if the task status is "Incompleted" and if the current time is greater than the task time
        if (row.status === 'Incompleted' && currentTime > taskTime) {
            var diffMillis = currentTime - taskTime; // Time difference in milliseconds
            var diffMinutes = Math.floor(diffMillis / 60000); // Convert to minutes

            var days = Math.floor(diffMinutes / (60 * 24));
            var hours = Math.floor((diffMinutes % (60 * 24)) / 60);
            var minutes = diffMinutes % 60;

            // Format the overdue time as 'Xd Xh Xm'
            overdueTime = (days > 0 ? days + 'd ' : '') +
                          (hours > 0 ? hours + 'h ' : '') +
                          (minutes > 0 ? minutes + 'm' : '').trim();
        } else {
            overdueTime = ' - '; // Not overdue, set a default value
        }

        return overdueTime; // Return the calculated overdue time
    }
},

                {
                    "data": null,
                    "render": function (data, type, row) {
                        return  '<button class="button-blue-rect view-task-btn" data-id="' + row.id + '"><span class="glyphicon glyphicon-eye-open"> </span>  </button> ' +
                            '<button class="button-green-rect edit-task-btn" data-id="' + row.id + '"><span class="glyphicon glyphicon-edit"> </span>  </button> ' +
                            '<button class="button-red-rect delete-btn" data-id="' + row.id + '"><span class="glyphicon glyphicon-trash"> </span>  </button>'
                           ;
                    }
                }
            ],
            "initComplete": function () {
                this.api().columns().every(function () {
                    var column = this;
                    var input = $('<input type="text" class="form-control form-control-sm" placeholder="Search...">')
                        .appendTo($(column.header()).empty())
                        .on('keyup change clear', function () {
                            if (column.search() !== this.value) {
                                column.search(this.value).draw();
                            }
                        });


                           // Add the dropdown for filtering active/inactive/all tasks
    $("#task_section_filter.dataTables_filter").append($("#taskFilter"));
    // $("#task_section_wrapper").prepend($("#taskFilter"));

    // Get the column index for the 'Active/Inactive' (A/I) column to filter
    var statusIndex = 0;
    $("#task_section th").each(function (i) {
        if ($(this).html() === "Active") {
            statusIndex = i;
            return false;  // Break the loop once we find the correct column
        }
    });

    // Use the DataTables API to filter based on the selected dropdown value
    $.fn.dataTable.ext.search.push(
        function (settings, data, dataIndex) {
            var selectedItem = $('#taskFilter').val();  // Get the selected value from the dropdown
            var status = data[statusIndex];  // Get the status value from the appropriate column

            if (selectedItem === "all") {
                return true;  // Show all tasks when "All" is selected
            } else if (selectedItem === "active" && status === "Active") {
                return true;  // Show only active tasks
            } else if (selectedItem === "inactive" && status === "Inactive") {
                return true;  // Show only inactive tasks
            }
            return false;  // Filter out the row if it doesn't match
        }
    );

    // Trigger the filter every time the dropdown value changes
         $("#taskFilter").change(function () {
           table.draw();
          });

    // Initial draw of the table
          table.draw();
                });
 
                $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
    var rowData = table.row(dataIndex).data();
    // Get session email from PHP
    var sessionEmail = '<?php echo $_SESSION["useremail"]; ?>';  // Assuming the email is stored in the session

    // Check if user_emails and group_emails are defined, default to empty arrays if undefined
    var userEmails = rowData.user_emails || [];
    var groupEmails = rowData.group_emails || [];

    // Check if the session email is the specific email to ignore
    if (sessionEmail === 'enriketacara@protech.com.al'||sessionEmail === 'admin@protech.com.al') {
        return true;  // Show all tasks for this specific user
    }

    // Check if sessionEmail exists in either user_emails or group_emails
    if (Array.isArray(userEmails) && userEmails.includes(sessionEmail) ||
        Array.isArray(groupEmails) && groupEmails.includes(sessionEmail)) {
        return true;  // Show the task row
    }

    return false;  // Hide the task row
});


// Trigger the initial draw of the table
table.draw();
            },
            "ordering": true,
            "createdRow": function(row, data, dataIndex) {
                // Add custom styling if needed
            }
        });

        // $('#incompleteTasksCard').on('click', function() {
        //     table.column(6).search('Incompleted').draw(); // Assuming status is in the 7th column
        // });
        
        // $('#pendingTasksCard').on('click', function() {
        //     table.column(6).search('Pending').draw(); // Assuming status is in the 7th column
        // });
        
        // $('#highPriorityTasksCard').on('click', function() {
        //     table.column(0).search('high', true, false).draw(); // Assuming priority is indicated by the color dot in the first column
        // });

        // Handle comment deletion
        $(document).on('click', '.delete-comment-btn', function() {
            var id = $(this).data('id');
            $.ajax({
                url: './api/v1/TMS/delete_comment.php',// Adjust the path to your backend script for deleting comments
                type: 'POST',
                data: { id: id },
                success: function(response) {
                    if (response.success) {
                        alertify.success('Comment deleted successfully.');
                        table.ajax.reload(null, false); // Reload DataTable without resetting paging
                    } else {
                        alertify.error('Failed to delete comment.');
                    }
                },
                error: function() {
                    alertify.error('An error occurred while deleting the comment.');
                }
            });
        });

        // Handle comment submission
        $(document).on('click', '.submit-comment-btn', function() {
            var id = $(this).data('id');
            var comment = $(this).closest('td').find('.comment-textarea').val();

            $.ajax({
                url: './api/v1/TMS/comment.php', // Adjust the path to your backend script
                type: 'POST',
                data: {
                    id: id,
                    comment: comment
                },
                success: function(response) {
                    if (response.success) {
                        alertify.success('Comment updated successfully.');
                        table.ajax.reload(null, false); // Reload DataTable without resetting paging
                    } else {
                        alertify.error('Failed to update comment.');
                    }
                },
                error: function() {
                    alertify.error('An error occurred while updating the comment.');
                }
            });
        });


        $(document).on('click', '.mark-complete-btn', function() {
            var taskId = $(this).data('id');
            updateStatus(taskId, 'Completed');
            setTimeout(function() {
       table.ajax.reload(null, false);  // Second reload after 500ms delay
          }, 500);
        });

        $(document).on('click', '.mark-incomplete-btn', function() {
            var taskId = $(this).data('id');
            updateStatus(taskId, 'Incompleted');
            setTimeout(function() {
       table.ajax.reload(null, false);  // Second reload after 500ms delay
          }, 500);

        });


//         $('#task_section tbody').on('click', '.delete-btn', function() {
//     var rowData = $('#task_section').DataTable().row($(this).closest('tr')).data();
//     var taskId = rowData.id;

//     if (confirm('Are you sure you want to delete this task?')) {
//         $.ajax({
//             url: './api/v1/TMS/delete_task.php',  // Update the path accordingly
//             type: 'POST',
//             data: { id: taskId },
//             success: function(response) {
//                 if (response.success) {
//                     alertify.success(response.message);
//                     // Remove the row from the DataTable after successful deletion
//                     // $('#task_section').DataTable().row($(this).closest('tr')).remove().draw();
//                     table.ajax.reload(null, false); // Reload DataTable without resetting paging
//                 } else {
//                     alertify.error(response.message || 'Failed to delete task.');
//                 }
//             },
//             error: function(xhr, status, error) {
//                 alertify.error('Error occurred: ' + error);
//             }
//         });
//     }
// });
$('#task_section tbody').on('click', '.delete-btn', function() {
    var rowData = $('#task_section').DataTable().row($(this).closest('tr')).data();
    var taskId = rowData.id;

    // Use SweetAlert2 for confirmation
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Perform the AJAX request if the user confirms
            $.ajax({
                url: './api/v1/TMS/delete_task.php',  // Update the path accordingly
                type: 'POST',
                data: { id: taskId },
                success: function(response) {
                    if (response.success) {
                        // Use SweetAlert2 for success
                        Swal.fire(
                            'Deleted!',
                            response.message,
                            'success'
                        );
                        // Reload DataTable without resetting paging
                        table.ajax.reload(null, false);
                    } else {
                        // Use SweetAlert2 for errors
                        Swal.fire(
                            'Error!',
                            response.message || 'Failed to delete task.',
                            'error'
                        );
                    }
                },
                error: function(xhr, status, error) {
                    // Use SweetAlert2 for AJAX error
                    Swal.fire(
                        'Error!',
                        'Error occurred: ' + error,
                        'error'
                    );
                }
            });
        }
    });
});

        // Function to handle the form submission
        // $('#editTaskForm').submit(function(event) {
        //     event.preventDefault();

        //     $.ajax({
        //         url: './api/v1/TMS/update_task.php', // URL to the backend update script
        //         type: 'POST',
        //         data: $(this).serialize(),
        //         success: function(response) {
        //             if (response.success) {
        //                 alertify.success(response.message);
        //                 $('#editTaskModal').modal('hide');
        //                 // Optionally, refresh the task list or update the UI
        //                 setTimeout(function() {
        //            table.ajax.reload(null, false);  // Second reload after 500ms delay
        //   }, 500);
        //             } else {
        //                 alert(response.message);
        //             }
        //         },
        //         error: function() {
        //             alertify.error('Error updating task.');
        //         }
        //     });
        // });
     $('#editTaskForm').submit(function(event) {
    event.preventDefault();

    // Collect the form data including frequency data
    var formData = $(this).serialize();

    // Collect frequency data
    var frequencyData = getTaskFrequencyDataEdit();
    
    // Append frequency data to formData
    $.each(frequencyData, function(key, value) {
        formData += '&' + encodeURIComponent(key) + '=' + encodeURIComponent(value);
    });

    $.ajax({
        url: './api/v1/TMS/update_task.php', // Replace with the actual path to your backend file
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                alertify.success('Task updated successfully!');
                $('#editTaskModal').modal('hide');
                setTimeout(function() {
                    table.ajax.reload(null, false);  // Optionally refresh the task list or update the UI
                }, 500);
            } else {
                $('#errorMessage').removeClass('d-none').text(response.message);
            }
        },
        error: function(xhr, status, error) {
            alertify.error('AJAX error: ' + error);
            $('#errorMessage').removeClass('d-none').text('An error occurred while updating the task.');
        }
    });
});

function getTaskFrequencyDataEdit() {
    let taskFrequency = $('input[name="edit_radio"]:checked').val(); // Assuming 'time' is the name for the task frequency radio buttons
    let taskData = {
        frequency: taskFrequency,
        hour: null,
        minute: null,
        day: null,
        weekday: null
    };

    // Collect data based on the selected frequency type
    if (taskFrequency === 'Hourly') {
        taskData.hour = $('#edit_hourly_hourPicker').val() || null;
        taskData.minute = $('#edit_hourly_minutePicker').val() || null;
    } else if (taskFrequency === 'Daily') {
        taskData.hour = $('#edit_daily_hourPicker').val() || null;
        taskData.minute = $('#edit_daily_minutePicker').val() || null;
    } else if (taskFrequency === 'Weekly') {
        taskData.weekday = $('#edit_weekly_dayPicker').val() || null;
    } else if (taskFrequency === 'Monthly') {
        taskData.day = $('#edit_monthly_dayPicker').val() || null;
        taskData.hour = $('#edit_monthly_hourPicker').val() || null;
        taskData.minute = $('#edit_monthly_minutePicker').val() || null;
    }

    return taskData;
}


        // Handle the toggle active/inactive button click
        $('#task_section tbody').on('click', '.toggle-active-btn', function() {
            var rowData = $('#task_section').DataTable().row($(this).closest('tr')).data();
            var taskId = rowData.id;
            var isActive = rowData.active == 1 ? 0 : 1; // Toggle between active (1) and inactive (0)

            $.ajax({
                url: './api/v1/TMS/update_task_active_status.php',
                type: 'POST',
                data: { task_id: taskId, active: isActive },
                success: function(response) {
                    if (response.success) {
                        // Update the row's active status
                        rowData.active = isActive;
                        // $('#task_section').DataTable().row($(this).closest('tr')).data(rowData).draw();
                        table.ajax.reload(null, false);
                        // Apply blur effect if inactive
                        if (isActive == 0) {
                            $(this).closest('tr').css({
                                'filter': 'blur(2px)',
                                'pointer-events': 'none',
                                'opacity': '0.5'
                            });
                        } else {
                            $(this).closest('tr').css({
                                'filter': 'none',
                                'pointer-events': 'auto',
                                'opacity': '1'
                            });
                        }
                    } else {
                        alertify.error('Error updating task status: ' + response.message);
                    }
                }.bind(this),
                error: function() {
                    alertify.error('Error updating task status.');
                }
            });
        });

// Handle form submission
$('#savetask').on('submit', function(event) {
        event.preventDefault();
    // Show loading overlay on the button
    $('#save_task_button_').LoadingOverlay('show');
        // Collect the form data
        var formData = {
            task_title: $('#task_title').val(),
            task_description: $('#task_des').val(),
            start_hour: $('#hourPicker_startTime').val(),
            start_minute: $('#minutePicker_starTime').val(),
            end_hour: $('#hourPicker_endTime').val(),
            end_minute: $('#minutePicker_endTime').val(),
            saturday_cron: $('#saturday').is(':checked') ? 1 : 0,
            sunday_cron: $('#sunday').is(':checked') ? 1 : 0,
            task_type: $('input[name="radio"]:checked').val(),
            task_frequency: $('input[name="time"]:checked').val(),
            priority: $('#priorityDropdown').val(),
            assign_task: $('#assign_task').val(),
            group: $('#group').val(),
        };

        // Get the frequency data and merge it with formData
        var frequencyData = getTaskFrequencyData();
        // console.log(frequencyData);
        formData = {...formData, ...frequencyData}; // Merge formData with the frequency data

        $.ajax({
            url: './api/v1/TMS/add_task.php', // Replace with the actual path to your backend file
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                // Hide loading overlay on the button
                $('#save_task_button_').LoadingOverlay('hide');
                if (response.success) {
                    alertify.success('Task added successfully!');
                    $('#taskAddModal').modal('hide');
                   
       table.ajax.reload(null, false);  // Second reload after 500ms delay
        
                } else {
                    $('#errorMessage').removeClass('d-none').text(response.message);
                }
            },
            error: function(xhr, status, error) {
                // Hide loading overlay on the button
                $('#save_task_button_').LoadingOverlay('hide');
                alertify.error('AJAX error: ' + error);
                $('#errorMessage').removeClass('d-none').text('An error occurred while saving the task.');
            }
        });
    });







    });

    // jQuery function to handle the status update
    function updateStatus(taskId, newStatus) {
        $.ajax({
            url: './api/v1/TMS/update_status.php', // Update this path to your actual PHP script location
            type: 'POST',
            data: {
                id: taskId,
                status: newStatus
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alertify.success('Status updated successfully');
                  
                } else {
                    alertify.error(response.message);
                }
            },
            error: function(xhr, status, error) {
                // Handle AJAX error
                alertify.error('AJAX error: ' + error);
            }
        });
    }
    function populateDropdowns() {
        $.ajax({
            url: './api/v1/TMS/populate_dropdowns.php', // Update this path to your actual PHP script location
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.error) {
                    // Handle error response (e.g., show an error message)
                    alertify.error('Error: ' + response.message);
                    return;
                }

                let assignTaskDropdown = $('#assign_task');
                let groupDropdown = $('#group');

                  // Clear existing options
                assignTaskDropdown.empty();
                groupDropdown.empty();

                 // Populate 'Assign to individuals' dropdown
                $.each(response.users, function(index, user) {
                    assignTaskDropdown.append(new Option(user.firstname + ' ' + user.lastname, user.userid));
                });
                // Populate 'Assign to a group' dropdown
                $.each(response.user_groups, function(index, group) {
                    groupDropdown.append(new Option(group.name, group.id));
                });

             // Re-initialize chosen-select after populating
                assignTaskDropdown.trigger('chosen:updated');
                groupDropdown.trigger('chosen:updated');
            },
            error: function(xhr, status, error) {
                // Handle AJAX error
                alertify.error('AJAX error: ' + error);
            }
        });
    }

    // Call the function to populate dropdowns on page load


    // Example usage
    // Call this function when a status button is clicked
    // updateStatus(taskId, newStatus);

    // Function to get task frequency details based on selected frequency
    function getTaskFrequencyData() {
        let taskFrequency = $('input[name="radio"]:checked').val(); // Assuming 'time' is the name for the task frequency radio buttons
        let taskData = {
            frequency: taskFrequency,
            hour: null,
            minute: null,
            day: null,
            weekday: null
        };

        // Collect data based on the selected frequency type
        if (taskFrequency === 'Hourly') {
            taskData.hour = $('#hourly_hourPicker').val() || null;
            taskData.minute = $('#hourly_minutePicker').val() || null;
        } else if (taskFrequency === 'Daily') {
            taskData.hour = $('#daily_hourPicker').val() || null;
            taskData.minute = $('#daily_minutePicker').val() || null;
        } else if (taskFrequency === 'Weekly') {
            taskData.weekday = $('#weekly_dayPicker').val() || null;
        } else if (taskFrequency === 'Monthly') {
            taskData.day = $('#monthly_dayPicker').val() || null;
            taskData.hour = $('#monthly_hourPicker').val() || null;
            taskData.minute = $('#monthly_minutePicker').val() || null;
        }

        return taskData;
    }

    

        // // Show clicked_once or clicked_multi based on radio button selection
        // $('input[name="time"]').change(function() {
        //     $('#edit_taskFrequencyDetails').hide();
        //     $('#edit_taskFrequencyDetails > div').hide();

        //     if (this.id === 'edit_radio_once') {
        //         $('#edit_clicked_once').show();
        //         $('#edit_clicked_multi').hide();
        //     } else if (this.id === 'edit_radio_multi') {
        //         $('#edit_clicked_once').hide();
        //         $('#edit_clicked_multi').show();
        //     }
        // });

        // // Show task details based on selected frequency
        // $('input[name="radio"]').change(function() {
        //     $('#edit_taskFrequencyDetails').show();
        //     $('#edit_taskFrequencyDetails > div').hide();

        //     if (this.value === 'Hourly') {
        //         $('#edit_hourlyContainer').show();
        //     } else if (this.value === 'Daily') {
        //         $('#edit_dailyContainer').show();
        //     } else if (this.value === 'Weekly') {
        //         $('#edit_weeklyContainer').show();
        //     } else if (this.value === 'Monthly') {
        //         $('#edit_monthlyContainer').show();
        //     }
        // });
    // });


//     $(document).ready(function() {
//     $('#task_section tbody').on('click', '.edit-task-btn', function() {
//     var rowData = $('#task_section').DataTable().row($(this).closest('tr')).data();
//     var taskId = rowData.id;

//     // Fetch task details and populate the edit modal
//     $.ajax({
//         url: './api/v1/TMS/get_task_details.php',
//         type: 'GET',
//         data: { task_id: taskId },
//         success: function(response) {
//             if (response.success) {
//                 var task = response.data;

//                 // Populate modal fields with task data
//                 $('#task_id').val(task.id);
//                 $('#edit_task_title').val(task.task_title);
//                 $('#edit_task_des').val(task.task_description);
//                 $('#edit_priorityDropdown').val(task.priority);
//                 $('#edit_hourPicker_startTime').val(task.cron_start_time.split(':')[0]);
//                 $('#edit_minutePicker_starTime').val(task.cron_start_time.split(':')[1]);
//                 $('#edit_hourPicker_endTime').val(task.cron_end_time.split(':')[0]);
//                  $('#edit_minutePicker_endTime').val(task.cron_end_time.split(':')[1]);
//                 $('#edit_saturday').prop('checked', task.saturday);
//                 $('#edit_sunday').prop('checked', task.sunday);
//                 $('#edit_assign_task').chosen();
//                 $('#edit_group').chosen();

//                 // Populate task assignment fields (individuals and groups)
//                 $('#edit_assign_task').html(task.assign_task).trigger('chosen:updated');
//                 $('#edit_group').html(task.group).trigger('chosen:updated');

//                 // Handle "One Time" and "Multi Time" radio buttons
//                 if (task.frequency === 'Multi Time') {
//                     $('#edit_radio_multi').prop('checked', true);
//                     $('#edit_clicked_multi').show();   // Show the Multi Time div
//                     $('#edit_clicked_once').hide();    // Hide the One Time div

//                     // Logic for Multi Time (Daily, Weekly, Monthly)
//                     if (task.task_type === 'Daily') {
//                         $('#edit_radio5').prop('checked', true);
//                         $('#edit_dailyContainer').show();
//                         $('#edit_hourlyContainer, #edit_weeklyContainer, #edit_monthlyContainer').hide();
//                     } else if (task.task_type === 'Weekly') {
//                         $('#edit_radio6').prop('checked', true);
//                         $('#edit_weeklyContainer').show();
//                         $('#edit_weekly_dayPicker').val(task.weekly_day);
//                         $('#edit_hourlyContainer, #edit_dailyContainer, #edit_monthlyContainer').hide();
//                     } else if (task.task_type === 'Monthly') {
//                         $('#edit_radio7').prop('checked', true);
//                         $('#edit_monthlyContainer').show();
//                         $('#edit_monthly_dayPicker').val(task.monthly_day);
//                         $('#edit_monthly_hourPicker').val(task.monthly_hour);
//                         $('#edit_monthly_minutePicker').val(task.monthly_minute);
//                         $('#edit_hourlyContainer, #edit_dailyContainer, #edit_weeklyContainer').hide();
//                     }
//                 } else {
//                     $('#edit_radio_once').prop('checked', true);
//                     $('#edit_clicked_once').show();    // Show the One Time div
//                     $('#edit_clicked_multi').hide();   // Hide the Multi Time div

//                     // Logic for One Time (Hourly, Daily, Weekly, Monthly)
//                     if (task.task_type === 'Hourly') {
//                         $('#edit_radio1').prop('checked', true);
//                         $('#edit_taskFrequencyDetails').show();
//                         $('#edit_hourlyContainer').show();
//                         $('#edit_dailyContainer, #edit_weeklyContainer, #edit_monthlyContainer').hide();
//                     } else if (task.task_type === 'Daily') {
//                         $('#edit_radio2').prop('checked', true);
//                         $('#edit_dailyContainer').show();
//                         $('#edit_hourlyContainer, #edit_weeklyContainer, #edit_monthlyContainer').hide();
//                     } else if (task.task_type === 'Weekly') {
//                         $('#edit_radio3').prop('checked', true);
//                         $('#edit_weeklyContainer').show();
//                         $('#edit_weekly_dayPicker').val(task.weekly_day);
//                         $('#edit_hourlyContainer, #edit_dailyContainer, #edit_monthlyContainer').hide();
//                     } else if (task.task_type === 'Monthly') {
//                         $('#edit_radio4').prop('checked', true);
//                         $('#edit_monthlyContainer').show();
//                         $('#edit_monthly_dayPicker').val(task.monthly_day);
//                         $('#edit_monthly_hourPicker').val(task.monthly_hour);
//                         $('#edit_monthly_minutePicker').val(task.monthly_minute);
//                         $('#edit_hourlyContainer, #edit_dailyContainer, #edit_weeklyContainer').hide();
//                     }
//                 }

//                 // Populate the progress bar
//                 var $projectBar = $('.project .bar');
//                 var $projectPercent = $('.project .percent');
//                 $projectBar.slider('value', task.progress);
//                 $projectPercent.val(task.progress + "%");

//                 // Show the modal
//                 $('#editTaskModal').modal('show');
//             } else {
//                 alertify.success(response.message);
//             }
//         },
//         error: function() {
//             alertify.error('Error fetching task details.');
//         }
//     });
// });
//     });

$(document).ready(function() {
    $('#task_section tbody').on('click', '.edit-task-btn', function() {
        var rowData = $('#task_section').DataTable().row($(this).closest('tr')).data();
        var taskId = rowData.id;

        // Fetch task details and populate the edit modal
        $.ajax({
            url: './api/v1/TMS/get_task_details.php',
            type: 'GET',
            data: { task_id: taskId },
            success: function(response) {
                if (response.success) {
                    var task = response.data;

                    // Populate modal fields with task data
                $('#task_id').val(task.id);
                $('#edit_task_title').val(task.task_title);
                $('#edit_task_des').val(task.task_description);
                $('#edit_priorityDropdown').val(task.priority);
                    $('#edit_status').val(task.status);
                $('#edit_hourPicker_startTime').val(task.cron_start_time.split(':')[0]);
                $('#edit_minutePicker_starTime').val(task.cron_start_time.split(':')[1]);
                $('#edit_hourPicker_endTime').val(task.cron_end_time.split(':')[0]);
                 $('#edit_minutePicker_endTime').val(task.cron_end_time.split(':')[1]);
                $('#edit_saturday').prop('checked', task.saturday);
                $('#edit_sunday').prop('checked', task.sunday);
                $('#edit_assign_task').chosen();
                $('#edit_group').chosen();

                    // Populate task assignment fields (individuals and groups)
                    $('#edit_assign_task').html(task.assign_task).trigger('chosen:updated');
                    $('#edit_group').html(task.group).trigger('chosen:updated');

                    // Handle "One Time" and "Multi Time" radio buttons
                    if (task.frequency === 'Multi Time') {
                        $('#edit_radio_multi').prop('checked', true);
                        $('#edit_clicked_multi').show();   // Show the Multi Time div
                        $('#edit_clicked_once').hide();    // Hide the One Time div

                        // Logic for Multi Time (Daily, Weekly, Monthly)
                        if (task.task_type === 'Daily') {
                            $('#edit_taskFrequencyDetails').show();
                            $('#edit_radio5').prop('checked', true);
                            $('#edit_dailyContainer').show();
                            $('#edit_hourlyContainer, #edit_weeklyContainer, #edit_monthlyContainer').hide();
                            $('#edit_daily_hourPicker').val(task.daily_hour);
                            $('#edit_daily_minutePicker').val(task.daily_minute);
                        } else if (task.task_type === 'Weekly') {
                            $('#edit_taskFrequencyDetails').show();
                            $('#edit_radio6').prop('checked', true);
                            $('#edit_weeklyContainer').show();
                            $('#edit_weekly_dayPicker').val(task.weekly_day);
                            $('#edit_hourlyContainer, #edit_dailyContainer, #edit_monthlyContainer').hide();
                        } else if (task.task_type === 'Monthly') {
                            $('#edit_taskFrequencyDetails').show();
                            $('#edit_radio7').prop('checked', true);
                            $('#edit_monthlyContainer').show();
                            $('#edit_monthly_dayPicker').val(task.monthly_day);
                            $('#edit_monthly_hourPicker').val(task.monthly_hour);
                            $('#edit_monthly_minutePicker').val(task.monthly_minute);
                            $('#edit_hourlyContainer, #edit_dailyContainer, #edit_weeklyContainer').hide();
                        }
                    } else {
                        $('#edit_radio_once').prop('checked', true);
                        $('#edit_clicked_once').show();    // Show the One Time div
                        $('#edit_clicked_multi').hide();   // Hide the Multi Time div

                        // Logic for One Time (Hourly, Daily, Weekly, Monthly)
                        if (task.task_type === 'Hourly') {
                            $('#edit_radio1').prop('checked', true);
                            $('#edit_taskFrequencyDetails').show();
                            $('#edit_hourlyContainer').show();
                            $('#edit_dailyContainer, #edit_weeklyContainer, #edit_monthlyContainer').hide();
                            $('#edit_hourly_hourPicker').val(task.hourly_hour);
                            $('#edit_hourly_minutePicker').val(task.hourly_minute);
                        } else if (task.task_type === 'Daily') {
                            $('#edit_taskFrequencyDetails').show();
                            $('#edit_radio2').prop('checked', true);
                            $('#edit_dailyContainer').show();
                            $('#edit_hourlyContainer, #edit_weeklyContainer, #edit_monthlyContainer').hide();
                            $('#edit_daily_hourPicker').val(task.daily_hour);
                            $('#edit_daily_minutePicker').val(task.daily_minute);
                        } else if (task.task_type === 'Weekly') {
                            $('#edit_taskFrequencyDetails').show();
                            $('#edit_radio3').prop('checked', true);
                            $('#edit_weeklyContainer').show();
                            $('#edit_weekly_dayPicker').val(task.weekly_day);
                            $('#edit_hourlyContainer, #edit_dailyContainer, #edit_monthlyContainer').hide();
                        } else if (task.task_type === 'Monthly') {
                            $('#edit_taskFrequencyDetails').show();
                            $('#edit_radio4').prop('checked', true);
                            $('#edit_monthlyContainer').show();
                            $('#edit_monthly_dayPicker').val(task.monthly_day);
                            $('#edit_monthly_hourPicker').val(task.monthly_hour);
                            $('#edit_monthly_minutePicker').val(task.monthly_minute);
                            $('#edit_hourlyContainer, #edit_dailyContainer, #edit_weeklyContainer').hide();
                        }
                    }

                    // Populate the progress bar
                    var $projectBar = $('.project .bar');
                    var $projectPercent = $('.project .percent');
                    $projectBar.slider('value', task.progress);
                    $projectPercent.val(task.progress + "%");

                    // Show the modal
                    $('#editTaskModal').modal('show');
                } else {
                    alertify.success(response.message);
                }
            },
            error: function() {
                alertify.error('Error fetching task details.');
            }
        });
    });
});

    $('#task_section tbody').on('click', '.view-task-btn', function() {
        var rowData = $('#task_section').DataTable().row($(this).closest('tr')).data();
        var taskId = rowData.id;

        $.ajax({
            url: './api/v1/TMS/get_task_details.php',
            type: 'GET',
            data: { task_id: taskId },
            success: function(response) {
                if (response.success) {
                    var task = response.data;
                    // Populate modal fields
                    $('#view_task_id').text(task.id);
                    $('#view_task_title').text(task.task_title);
                    $('#view_task_description').text(task.task_description);
                    $('#view_frequency').text(task.frequency);
                    $('#view_task_type').text(task.task_type);
                    $('#view_task_time').text(task.task_time);
                    $('#view_progress').text(task.progress);
                    $('#view_created_by').text(task.created_by);
                    $('#view_created_at').text(task.created_at);
                    $('#view_updated_at').text(task.updated_at);
                    $('#view_cron_start_time').text(task.cron_start_time);
                    $('#view_cron_end_time').text(task.cron_end_time);
                    $('#view_saturday').text(task.saturday ? 'Yes' : 'No');
                    $('#view_sunday').text(task.sunday ? 'Yes' : 'No');
                    $('#view_priority').text(task.priority);

                    $('#viewTaskModal').modal('show');
                } else {
                    alertify.success(response.message);
                }
            },
            error: function() {
                alertify.error('Error fetching task details.');
            }
        });
    });




    // $(function() {
    //     $('.project').each(function() {
    //         var $projectBar = $(this).find('.bar');
    //         var $projectPercent = $(this).find('.percent');
    //         var $projectRange = $(this).find('.ui-slider-range');
    //         $projectBar.slider({
    //             range: "min",
    //             animate: true,
    //             value: 1,
    //             min: 0,
    //             max: 100,
    //             step: 1,
    //             slide: function(event, ui) {
    //                 $projectPercent.val(ui.value + "%");
    //             },
    //             change: function(event, ui) {
    //                 var $projectRange = $(this).find('.ui-slider-range');
    //                 var percent = ui.value;
    //                 if (percent < 30) {
    //                     $projectPercent.css({
    //                         'color': 'red'
    //                     });
    //                     $projectRange.css({
    //                         'background': '#f20000'
    //                     });
    //                 } else if (percent > 31 && percent < 70) {
    //                     $projectPercent.css({
    //                         'color': 'gold'
    //                     });
    //                     $projectRange.css({
    //                         'background': 'gold'
    //                     });
    //                 } else if (percent > 70) {
    //                     $projectPercent.css({
    //                         'color': 'green'
    //                     });
    //                     $projectRange.css({
    //                         'background': 'green'
    //                     });
    //                 }
    //             }
    //         });
    //     })
    // })
    $(function() {
        $('.project .bar').slider({
            range: "min",
            animate: true,
            min: 0,
            max: 100,
            step: 1,
            slide: function(event, ui) {
                $(this).closest('.project').find('.percent').val(ui.value + "%");
                var progressValue = ui.value;
                $(this).closest('.project').find('.percent').val(progressValue + "%");
                $('#edit_progress').val(progressValue);  // Update the hidden input or store the value directly in the text field

            },

            change: function(event, ui) {
                var $project = $(this).closest('.project');
                var percent = ui.value;
                var $projectPercent = $project.find('.percent');
                var $projectRange = $project.find('.ui-slider-range');
                // var progressValue = ui.value;
                $(this).closest('.project').find('.percent').val(percent + "%");
                $('#edit_progress').val(percent);  // Update the hidden input or store the value directly in the text field
                if (percent < 30) {
                    $projectPercent.css({ 'color': 'red' });
                    $projectRange.css({ 'background': '#f20000' });
                } else if (percent > 31 && percent < 70) {
                    $projectPercent.css({ 'color': 'gold' });
                    $projectRange.css({ 'background': 'gold' });
                } else if (percent > 70) {
                    $projectPercent.css({ 'color': 'green' });
                    $projectRange.css({ 'background': 'green' });
                }
            }
        });
    });


    $(document).ready(function() {
        // Fetch data when the page loads
        $.ajax({
            url: './api/v1/TMS/get_task_summaries.php', // Adjust the URL path as needed
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Display the data on the cards
                    $('#incompleteTasksCard').text(response.incomplete_tasks);
                    $('#pendingTasksCard').text(response.pending_tasks);
                    $('#highPriorityTasksCard').text(response.high_priority_tasks);
                    $('#highPriorityIncompleteTasksCard').text(response.high_incomplete_tasks);
                } else {
                    alertify.success(response.message);
                }
            },
            error: function(xhr, status, error) {
                alertify.error('AJAX error: ' + error);
            }
        });

        $('#incompleteTasksCard').on('click', function() {
            table.column(6).search('Incompleted').draw(); // Assuming status is in the 7th column
        });

        $('#pendingTasksCard').on('click', function() {
            table.column(6).search('Pending').draw(); // Assuming status is in the 7th column
        });

        $('#highPriorityTasksCard').on('click', function() {
            table.column(0).search('high', true, false).draw(); // Assuming priority is indicated by the color dot in the first column
        });
    });

   $(document).on('click', '.view-status-history-btn', function() {
    var statusHistoryData = $(this).attr('data-status-history');
    // console.log(statusHistoryData); // Verify data structure in the console

    // // Check if DataTable is already initialized, and destroy it if necessary
    // if ($.fn.DataTable.isDataTable('#statusHistoryTable')) {
    //     $('#statusHistoryTable').DataTable().clear().destroy(); // Clear and destroy existing DataTable
    // }

    // // Insert the new status history data into the table's tbody
    $('#statusHistoryTable tbody').html(statusHistoryData);

    // // After data is inserted, reinitialize the DataTable
    // setTimeout(function() {
    //     var table = $('#statusHistoryTable').DataTable({
    //         "processing": true,
    //         "responsive": true,
    //         "serverSide": false,
    //         "paging": true,
    //         "pageLength": 10,
    //         "searching": true
    //     });

    //     // Apply the search functionality on each header input
    //     $('#statusHistoryTable thead tr:eq(1) th').each(function(i) {
    //         $('input', this).on('keyup change', function() {
    //             if (table.column(i).search() !== this.value) {
    //                 table.column(i).search(this.value).draw();
    //             }
    //         });
    //     });
    // }, 100); // Delay to ensure rows are rendered before initializing DataTable

    // Show the modal popup
    $('#statusHistoryModal').modal('show');
});
$(document).ready(function() {
    // Loop through each header input to apply the search
    $('#statusHistoryTable thead input').on('keyup', function() {
        // Get the index of the column (th)
        var columnIndex = $(this).parent().index();
        
        // Get the value of the input for filtering
        var searchValue = $(this).val().toLowerCase();
        
        // Loop through each row in the table body
        $('#statusHistoryTable tbody tr').filter(function() {
            // Toggle row visibility based on the input value in the specific column
            $(this).toggle($(this).find('td').eq(columnIndex).text().toLowerCase().indexOf(searchValue) > -1);
        });
    });
});
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});
</script>
</body>



</html>