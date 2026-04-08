$(document).on('click', '.moreless-button', function() {
    // Find the related elements within the same product card
    var $productCard = $(this).closest('.table-responsive');
    var $moreText = $productCard.find('.moretext');
    var $button = $productCard.find('.moreless-button');

    $moreText.toggleClass('show');

    if ($button.text() == "view more...") {
        $button.text("view less...");
    } else {
        $button.text("view more...");
    }
});