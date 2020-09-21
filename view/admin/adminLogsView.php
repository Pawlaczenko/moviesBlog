<div class="adminPanel wrapper">
    <div class="adminPanel__container">
        <div class="adminPanel__content">
            <h2 class="adminPanel__header">Logs</h2>
            <table class="adminPanel__table display cell-border" id="log-table">
                <thead>
                    <th>ID</th>
                    <th>Type</th>
                    <th>User Id</th>
                    <th>Object Name</th>
                    <th>Object Id</th>
                    <th>Time</th>
                    <th>Comment</th>
                    <th>Delete</th>
                </thead>
                <tbody>
                    <?php foreach ($data["logs"] as $log) : ?>
                        <tr data-id="<?= $log->Id ?>" class="adminPanel__tr">
                            <td id="manage-id"><?= $log->Id; ?></td>
                            <td id="manage-name"><?= $log->Type; ?></td>
                            <td id="manage-surname"><?= $log->UserId; ?></td>
                            <td><?= $log->ObjectName; ?></td>
                            <td id="manage-login"><?= $log->ObjectId; ?></td>
                            <td id="manage-email"><?= $log->Time; ?></td>
                            <td><?= $log->Comment; ?></td>
                            <td>
                                <form method="POST" action="<?= SITE_PATH . "admin/deleteLog"; ?>" style="text-align: center;">
                                    <input type="hidden" name="id" value="<?=$log->Id?>">
                                    <button class="adminPanel__table-bin">
                                        <svg class="adminPanel__table-svg">
                                            <use xlink:href="<?=THEME?>/img/sprite.svg#icon-trash"></use>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let table = $('#log-table').DataTable();
    });
</script>