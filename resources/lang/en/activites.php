<?php

return [
    // Activities on project
    'create_project'                     => '{actor.data.name} has created a project titled as {meta.project_title}.',
    'update_project_title'               => '{actor.data.name} has updated project title from {meta.project_title_old} to {meta.project_title_new}.',
    'update_project_description'         => '{actor.data.name} has updated {meta.project_title} project description.',
    'update_project_status'              => '{actor.data.name} has updated project status from "{meta.project_status_old}" to "{meta.project_status_new}".',
    'update_project_budget'              => '{actor.data.name} has updated project budget from "{meta.project_budget_old}" to "{meta.project_budget_new}".',
    'update_project_pay_rate'            => '{actor.data.name} has updated project pay rate from "{meta.project_pay_rate_old}" to "{meta.project_pay_rate_new}".',
    'update_project_est_completion_date' => '{actor.data.name} has updated project est completion date from "{meta.project_est_completion_date_old}" to "{meta.project_est_completion_date_new}".',
    'update_project_color_code'          => '{actor.data.name} has updated project color code from "{meta.project_color_code_old}" to "{meta.project_color_code_new}".',

    // Activities on discussion board
    'create_discussion_board'             =>  '{actor.data.name} has created a discussion board titled as {meta.discussion_board_title}.', 
    'delete_discussion_board'             => '{actor.data.name} has deleted a discussion board titled as {meta.deleted_discussion_board_title}.',
    'update_discussion_board_title'       => '{actor.data.name} has updated the title of a discussion board from "{meta.discussion_board_title_old}" to "{meta.discussion_board_title_new}".',
    'update_discussion_board_description' => '{actor.data.name} has updated the description of a discussion board, {meta.discussion_board_title}.',
    'update_discussion_board_order'       => '{actor.data.name} has updated the order of a discussion board, {meta.discussion_board_title}.',
    'update_discussion_board_status'       => '{actor.data.name} has updated the status of a discussion board, {meta.discussion_board_title}.',


    // Activities on task list
    'create_task_list'             => '{actor.data.name} has created a task list titled as {meta.task_list_title}.',
    'delete_task_list'             => '{actor.data.name} has Deleted a task list titled as {meta.deleted_task_list_title}.',
    'update_task_list_title'       => '{actor.data.name} has updated the title of a task list from "{meta.task_list_title_old}" to "{meta.task_list_title_new}".',
    'update_task_list_description' => '{actor.data.name} has updated the description of a task list, {meta.task_list_title}.',
    'update_task_list_order'       => '{actor.data.name} has updated the order of a task list, {meta.task_list_title}.',
    'archived_task_list'           => '{actor.data.name} has archived a task list, {meta.task_list_title}.',
    'restore_task_list'            => '{actor.data.name} has restore a task list, {meta.task_list_title}.',


    // Activities on milestone
    'create_milestone'             => '{actor.data.name} has created a milestone, {meta.milestone_title}.',
    'delete_milestone'             => '{actor.data.name} has Deleted a milestone, {meta.deleted_milestone_title}.',
    'update_milestone_title'       => '{actor.data.name} has updated the title of a milestone from "{meta.milestone_title_old}" to "{meta.milestone_title_new}".',
    'update_milestone_description' => '{actor.data.name} has updated the description of a milestone, {meta.milestone_title}.',
    'update_milestone_order'       => '{actor.data.name} has updated the order of a milestone, {meta.milestone_title}.',
    'update_milestone_status'      => '{actor.data.name} has updated the status of a milestone, {meta.milestone_title}.',

    // Activities on task
    'create_task'             => '{actor.data.name} has created a task, {meta.task_title}.',
    'delete_task'             => '{actor.data.name} has deleted a task, {meta.deleted_task_title}.',
    'update_task_title'       => '{actor.data.name} has updated the title of a task from "{meta.task_title_old}" to "{meta.task_title_new}".',
    'update_task_description' => '{actor.data.name} has updated the description of a task, {meta.task_title}.',
    'update_task_estimation'  => '{actor.data.name} has updated the estimation of a task, {meta.task_title}, from {meta.task_estimation_old} to {meta.task_estimation_new}.',
    'update_task_start_at_date'    => '{actor.data.name} has updated the start date of a task, {meta.task_title}, from {meta.task_start_at_new} to {meta.task_start_at_old}.',
    'update_task_due_date'    => '{actor.data.name} has updated the due date of a task, {meta.task_title}, from {meta.task_due_date_new} to {meta.task_due_date_old}.',
    'update_task_complexity'  => '{actor.data.name} has updated the complexity of a task, {meta.task_title}, from {meta.task_complexity_old} to {meta.task_complexity_old}.',
    'update_task_priority'    => '{actor.data.name} has updated the priority of a task, {meta.task_title}, from {meta.task_priority_old} to {meta.task_priority_new}.',
    'update_task_payable'     => '{actor.data.name} has updated the payable status of a task, {meta.task_title}, from {meta.task_payable_old} to {meta.task_payable_new}.',
    'update_task_recurrent'   => '{actor.data.name} has updated the recurrency of a task, {meta.task_title}, {meta.task_recurrent_old} to {meta.task_recurrent_new}.',
    'update_task_status'      => '{actor.data.name} has updated the status of a task, {meta.task_title}, from {meta.task_status_old} to {meta.task_status_new}.',

    // Comment activities on task
    'comment_on_task'              => '{actor.data.name} has commented on a task, {meta.task_title}.',
    'update_comment_on_task'       => '{actor.data.name} has updated a comment on a task, {meta.task_title}.',
    'delete_comment_on_task'       => '{actor.data.name} has deleted a comment on a task, {meta.task_title}.',
    'reply_comment_on_task'        => '{actor.data.name} has replied a comment on a task, {meta.task_title}',
    'update_reply_comment_on_task' => '{actor.data.name} has updated a reply comment on a task, {meta.task_title}.',
    'delete_reply_comment_on_task' => '{actor.data.name} has deleted a reply comment on a task, {meta.task_title}.',

    // Comment activities on task list
    'comment_on_task_list'              => '{actor.data.name} has commented on a task list, {meta.task_list_title}.',
    'update_comment_on_task_list'       => '{actor.data.name} has updated a comment on a task list, {meta.task_list_title}.',
    'delete_comment_on_task_list'       => '{actor.data.name} has deleted a comment on a task list, {meta.task_list_title}.',
    'reply_comment_on_task_list'        => '{actor.data.name} has replied a comment on a task list, {meta.task_list_title}',
    'update_reply_comment_on_task_list' => '{actor.data.name} has updated a reply comment on a task list, {meta.task_list_title}.',
    'delete_reply_comment_on_task_list' => '{actor.data.name} has deleted a reply comment on a task list, {meta.task_list_title}.',

    // Comment activities on discussion board
    'comment_on_discussion_board'              => '{actor.data.name} has commented on a discussion board, {meta.discussion_board_title}.',
    'update_comment_on_discussion_board'       => '{actor.data.name} has updated a comment on a discussion board, {meta.discussion_board_title}.',
    'delete_comment_on_discussion_board'       => '{actor.data.name} has deleted a comment on a discussion board,{meta.discussion_board_title}.',
    'reply_comment_on_discussion_board'        => '{actor.data.name} has replied a comment on a discussion board, {meta.discussion_board_title}',
    'update_reply_comment_on_discussion_board' => '{actor.data.name} has updated a reply comment on a discussion board, {meta.discussion_board_title}.',
    'delete_reply_comment_on_discussion_board' => '{actor.data.name} has deleted a reply comment on a discussion board, {meta.discussion_board_title}.',

    // Comment activities on milestone
    'comment_on_milestone'              => '{actor.data.name} has commented on a milestone, {meta.milestone_title}.',
    'update_comment_on_milestone'       => '{actor.data.name} has updated a comment on a milestone, {meta.milestone_title}.',
    'delete_comment_on_milestone'       => '{actor.data.name} has deleted a comment on a milestone, {meta.milestone_title}.',
    'reply_comment_on_milestone'        => '{actor.data.name} has replied a comment on a milestone, {meta.milestone_title}',
    'update_reply_comment_on_milestone' => '{actor.data.name} has updated a reply comment on a milestone,{meta.milestone_title}.',
    'delete_reply_comment_on_milestone' => '{actor.data.name} has deleted a reply comment on a milestone, {meta.milestone_title}.',

    // Comment activities on project
    'comment_on_project'              => '{actor.data.name} has commented on the project, {meta.project_title}.',
    'update_comment_on_project'       => '{actor.data.name} has updated a comment on the project, {meta.project_title}.',
    'delete_comment_on_project'       => '{actor.data.name} has deleted a comment on the project, {meta.project_title}.',
    'reply_comment_on_project'        => '{actor.data.name} has replied a comment on the project, {meta.project_title}',
    'update_reply_comment_on_project' => '{actor.data.name} has updated a reply comment on the project, {meta.project_title}.',
    'delete_reply_comment_on_project' => '{actor.data.name} has deleted a reply comment on the project, {meta.project_title}.',

    // Comment activities on task
    'comment_on_file'              => '{actor.data.name} has commented on a file, {meta.file_title}.',
    'update_comment_on_file'       => '{actor.data.name} has updated a comment on a file, {meta.file_title}.',
    'delete_comment_on_file'       => '{actor.data.name} has deleted a comment on a file, {meta.file_title}.',
    'reply_comment_on_file'        => '{actor.data.name} has replied a comment on a file, {meta.file_title}',
    'update_reply_comment_on_file' => '{actor.data.name} has updated a reply comment on a file, {meta.file_title}.',
    'delete_reply_comment_on_file' => '{actor.data.name} has deleted a reply comment on a file, {meta.file_title}.',


    // duplicate project 
    'duplicate_project' => '{actor.data.name} has duplicated project from , {meta.old_project_title}.',
    'duplicate_list' => '{actor.data.name} has duplicated list from , {meta.old_task_list_title}.',
];
