<?php

return [
    'create' => [
        'success' => 'Successfully created new :model'
    ],
    'update' => [
        'success' => 'Successfully updated :model'
    ],
    'delete' => [
        'success' => 'Successfully deleted :model'
    ],
    'post' => [
        'images' => [
            'max' => 'Each post only has a maximum of :max images',
            'invalid' => 'Invalid delete image'
        ],
        'max_share_level' => 'Maximum number of posts allowed :max shares level',
    ],
    'reaction' => [
        'existed' => 'Reaction already exists',
    ],
    'notification' => [
        'mark_as_read' => 'Marked notifications as read',
        'mark_all_as_read' => 'Marked all notifications as read'
    ],
    'friendship' => [
        'not_exist' => 'Friendship does not exist',
        'deleted' => 'Unfriend successfully',
        'set_nickname_success' => 'Set nickname successfully',
        'nickname_deleted' => 'Deleted nickname successfully',
    ],
    'group' => [
        'join_success' => 'Joined group successfully',
        'leave_success' => 'Leaved group successfully',
        'request_success' => 'Requested to join group successfully',
        'remove_success' => 'Removed user successfully',
        'accept_success' => 'Accepted user successfully',
        'set_role_success' => 'Set role successfully'
    ],
    'group_chat' => [
        'add_success' => 'Add users to group chat successfully',
        'remove_success' => 'Remove user successfully',
        'delete_success' => 'Deleted group chat successfully',
        'update_role_success' => 'Update role successfully',
        'leave_success' => 'Leave group chat successfully',
        'not_in' => 'User is not in group chat',
        'not_has_admin' => 'Group chat must have at least 1 admin'
    ],
    'not_found' => ':model not found'
];
