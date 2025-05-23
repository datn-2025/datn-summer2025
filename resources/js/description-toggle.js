document.addEventListener('DOMContentLoaded', function () {
    const description = document.getElementById("bookDescription");
    const btn = document.getElementById("showMoreBtn");

    const fullText = description.dataset.full;
    const shortText = description.dataset.short;

    let expanded = false;

    btn.addEventListener('click', function () {
        if (!expanded) {
            description.textContent = fullText;
            btn.innerText = "Thu gọn";
            expanded = true;
        } else {
            description.textContent = shortText;
            btn.innerText = "Xem thêm";
            expanded = false;
        }
    });
});
