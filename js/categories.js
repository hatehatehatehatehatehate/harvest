function deleteCategory(id) {
    const title = document.getElementById(`categiry-title-${ id }`).innerText;
    if (confirm(`Вы действительно хотите удалить категорию "${ title }"?\n` +
        `Все заявки с этой категорией таже будут удалены.\n` +
        `Отменить это действие невозможно.`)) {
        window.location.href = "?del=" + id;
    }
}