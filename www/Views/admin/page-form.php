<?php
$page = $page ?? null;
$id = $page['id'] ?? '';
$title = $page['title'] ?? '';
$slug = $page['slug'] ?? '';
$content = $page['content'] ?? '';
$status = $page['status'] ?? 'draft';
?>

<h2><?= $id ? 'Modifier la page' : 'Créer une page' ?></h2>
<a href="/admin/pages">Retour à la liste</a> | <a href="/dashboard">Retour au dashboard</a>

<form method="POST" action="<?= $id ? '/admin/pages/edit-submit' : '/admin/pages/create-submit' ?>">
    <?php if($id): ?><input type="hidden" name="id" value="<?= $id ?>"><?php endif; ?>
    <div>
        <label>Titre</label>
        <input type="text" name="title" required value="<?= htmlspecialchars($title) ?>">
    </div>
    <div>
        <label>Contenu</label>
        <textarea name="content" rows="10" required><?= htmlspecialchars($content) ?></textarea>
    </div>
    <div>
        <label>Status</label>
        <select name="status">
            <option value="draft" <?= $status == 'draft' ? 'selected' : '' ?>>Brouillon</option>
            <option value="published" <?= $status == 'published' ? 'selected' : '' ?>>Publié</option>
        </select>
    </div>
    <div>
        <button type="submit"><?= $id ? 'Mettre à jour' : 'Enregistrer' ?></button>
    </div>
</form>
