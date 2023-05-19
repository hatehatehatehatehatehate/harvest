function deleteRequest(requestId) {
    if (confirm("Вы действительно хотите удалить эту заявку?")) {
        window.location.href = "profile.php?del=" + requestId;
    }
}