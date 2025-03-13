<?php
require __DIR__ . '/../Partials/header.php';
$filteredUsers = [];
if ($_SESSION['role'] === 'superAdmin') {
    $filteredUsers = $users;
} elseif ($_SESSION['role'] === 'administrator') {
    $filteredUsers = array_filter($users, function ($user) {
        return $user['role'] !== 'superAdmin' && $user['role'] !== 'administrator';
    });
} else {
    $filteredUsers = array_filter($users, function ($user) {
        return $user['id'] === $_SESSION['user_id'];
    });
}
?>

<div class="container">
    <h1>User List</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($filteredUsers as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                    <td>
                        <a href="/Hospital/public/users/update?id=<?php echo $user['id']; ?>" class="btn btn-primary btn-sm">Update</a>
                        <form action="/Hospital/public/users/delete" method="POST" class="form-inline d-inline">
                            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="text-center my-4">
        <li class="list-inline-item"><a href="/Hospital/public/" class="btn btn-outline-primary"><i class="fab fa-facebook-f"></i> Go Back</a></li>
    </div>
</div>

<?php
require __DIR__ . '/../Partials/footer.php';
?>