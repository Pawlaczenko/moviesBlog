<div class="adminPanel wrapper">
    <div class="adminPanel__container">
        <div class="adminPanel__content">
            <p class="error_message"><?=(isset($data['error']))?Tools::showErrors($data['error']):'';?></p>
            <h2 class="adminPanel__header">Categories</h2>
            <table class="adminPanel__table display cell-border" id="category-table">
                <thead>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Number of uses</th>
                    <th>Delete</th>
                </thead>
                <tbody>
                    <?php foreach ($data["categories"] as $category) : ?>
                        <tr data-id="<?= $category->Category_id ?>" class="adminPanel__tr">
                            <td id="manage-id"><?= $category->Category_id; ?></td>
                            <td id="manage-name"><?= $category->Name; ?></td>
                            <td id="manage-number"><?= $category->Number; ?></td>
                            <td>
                                <form method="POST" action="<?= SITE_PATH . "admin/deleteCategory"; ?>" style="text-align: center;">
                                    <input type="hidden" name="id" value="<?=$category->Category_id?>">
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
            <h2 class="adminPanel__header adminPanel__header--margin">Add a category</h2>
            <form  method="POST" action="<?= SITE_PATH . "admin/addCategory"; ?>">
                <label for="add" class="adminPanel__add-label">Insert a name: <input type="text" name="name" required="required" placeholder="category" class="adminPanel__add-input"></label>
                <button class="button button--category">Add +</button>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let table = $('#category-table').DataTable();
    });
</script>