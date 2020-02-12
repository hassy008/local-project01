<h1>Users List</h1>

<?php

$roles = [
    'all'                   => 'All',
    'author' => "Author",
    'subscriber' => 'Subscriber',
    'administrator'  => 'Administrator'
];


?>
<ol>
    <?php foreach ($roles as $role_key => $role) :
        $args = [
            'role' => $role_key
        ];
        //for All Users
        if ($role_key == 'all') {
            $args = [
                'role__in' => ['all', 'author', 'subscriber', 'administrator']
            ];
        }

        $User = new WP_User_Query($args);
        // echo '<pre>';
        // print_r($User);
        // echo '</pre>';
        ?>
    <li><a href="<?= admin_url('admin-post.php?action=users_list_from_admin&role_name=' . $role . '&role=' . $role_key); ?>"
            class="export-btn"><?= $role; ?><span>(<?= $User->get_total(); ?>)</span></a></li>
    <?php endforeach; ?>
</ol>