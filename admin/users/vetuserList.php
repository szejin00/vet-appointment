<?php include('../../config.php') ?>
<?php require_once '../middleware.php'; ?>
<?php include(ROOT_PATH . '/admin/users/userLogic.php') ?>
<?php
$vetUsers = getVetUsers();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Admin Area - Users </title>
    <!-- Bootstrap CSS -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" /> -->
    <!-- Custome styles -->
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>

<body>
    <?php include(INCLUDE_PATH . "/layouts/admin_navbar.php") ?>
    <div class="center-item">
        <div class="container">
            <div class="col-md-8 col-md-offset-2" id="form-con">
                <!-- <a href="userForm.php" class="normal-link1">Create new user</a><br><br><br>
                <hr> -->
                <h1 class="text-center">Vet Users</h1>
                <br />
                <?php if (isset($users)) : ?>
                    <table class="table table-bordered" id="userlist">
                        <thead>
                            <tr>
                                <th>N</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th colspan="2" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($vetUsers as $key => $value) : ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td><?php echo $value['username'] ?></td>
                                    <td><?php echo $value['role']; ?></td>
                                    <td class="text-center" id="btn-conti"><center>
                                        <a href="<?php echo BASE_URL ?>admin/users/userForm2.php?edit_user=<?php echo $value['id'] ?>" class="btn btn-sm btn-success icon-link">
                                            <span class="material-icons" id="apt-icon">edit</span></center>
                                        </a>
                                    </td>
                                    <!-- <td class="text-center"id="btn-conti"><center>
                                        <a href="<?php echo BASE_URL ?>admin/users/userForm.php?delete_user=<?php echo $value['id'] ?>" class="btn btn-sm btn-danger icon-link">
                                            <span class="material-icons" id="apt-icon">delete</span></center>
                                        </a>
                                    </td> -->
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else : ?>
                    <h2 class="text-center">No users in database</h2>
                <?php endif; ?>
            </div>
        </div>
        <?php include(INCLUDE_PATH . "/layouts/footer.php") ?>
    </div>
</body>

</html>