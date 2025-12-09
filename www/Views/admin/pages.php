<h2>Pages</h2>
<a href="/admin/pages/create">Créer une nouvelle page</a> | <a href="/dashboard">Retour au dashboard</a>

<?php if(!empty($pages)): ?>
    <table>
        <thead>
            <tr><th>ID</th><th>Title</th><th>Slug</th><th>Status</th><th>Actions</th></tr>
        </thead>
        <tbody>
        <?php foreach($pages as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['id']) ?></td>
                <td><?= htmlspecialchars($p['title']) ?></td>
                <td><?= htmlspecialchars($p['slug']) ?></td>
                <td><?= htmlspecialchars($p['status']) ?></td>
                <td>
                    <a href="/<?= htmlspecialchars($p['slug']) ?>" target="_blank">Voir</a> |
                     <?php if($p['author_id'] == $_SESSION['id'] || $_SESSION['is_admin'] == true): ?>
                        <a href="/admin/pages/edit?id=<?= $p['id'] ?>">Edit</a>
                    <?php endif; ?>
                    <form method="POST" action="/admin/pages/delete" style="display:inline">
                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                        <button type="submit" onclick="return confirm('Supprimer cette page ?')">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Aucune page</p>
<?php endif; ?>
