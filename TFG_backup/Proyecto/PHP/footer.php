<?php

/**
 * Pie de página: script de mensajes flash y cierre HTML.
 */
?>
<script src="JS/validate-image-upload.js"></script>
<script>
document.querySelectorAll(".flash").forEach(function (el) {
    setTimeout(function () {
        el.classList.add("flash-hide");
        setTimeout(function () { el.remove(); }, 400);
    }, 4500);
});
</script>
</body>
</html>
