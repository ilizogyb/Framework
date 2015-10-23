<div class="container theme-showcase" role="main">
    <div class="row">
        <div class="user-info-container">
            <table class="table">
                <caption><?php echo $table_capt; ?></caption>
                <thead>
                    <tr>
                        <th><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span></th>
                        <th>Email</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $user->id; ?></td>
                        <td><?php echo $user->email; ?></td>
                        <td><?php echo $user->role; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

