function callAjax(url, method, data, extraCbOnSuccess = null) {
    resetResultMessage();
    $.ajax({
        url: url,
        method: method,
        dataType: "json",
        data: data,
        success: function (result) {
            if (!result.result) {
                displayError("Update Failed", result.errors);
            } else {
                displayResult("Update Succeed");

                if (extraCbOnSuccess) {
                    extraCbOnSuccess();
                }
            }
        },
        error: function (result) {
            console.log(result.responseJSON);
            if (result.responseJSON !== undefined && result.responseJSON.errors !== undefined) {
                displayError("Update Failed", result.responseJSON.errors);
            } else {
                displayError("Update Failed", []);
            }
        },
    });
}

function resetResultMessage() {
    $("#result-message").html("");
    $("#result-message").hide();
    $(".warnHelpblock").html("");
    $(".warnHelpblock").hide();
}

function displayError(errorMessage, errors) {
    $("#result-message").html(
        '<div id="result-message-success" class="alert alert-warning alert-dismissible fade show">Update Failed</div>'
    );
    $("#result-message").show();
    for (const errorName in errors) {
        const warnInline = $("#" + errorName + "WarnHelpblock");
        if (warnInline.length) {
            warnInline.html(errors[errorName].join(","));
            warnInline.show();
        }
    }
}

function displayResult(message) {
    $("#result-message").html(
        '<div id="result-message-success" class="alert alert-success alert-dismissible fade show">Update Succeed</div>'
    );
    $("#result-message").show();
}

window.updateProduct = function updateProduct(productId) {
    callAjax("/api/product/" + productId, "PATCH", {
        name: $("#name").val(),
        description: $("#description").val(),
        image_url: $("#image_url").val(),
        tags: $("#tags").val(),
    });
};

window.deleteProduct = function deleteProduct(productId) {
    if (!confirm("Are you sure you want to remove this product?")) {
        return;
    }
    callAjax("/api/product/" + productId, "DELETE", {}, function () {
        alert("Product has been removed, you will be redirected now");
        window.location.replace("/products");
    });
};

window.createProduct = function createProduct() {
    callAjax("/api/products", "POST", {
        name: $("#name").val(),
        description: $("#description").val(),
        image_url: $("#image_url").val(),
        tags: $("#tags").val(),
    }, function () {
        alert("Product has been added, you will be redirected now");
        window.location.replace("/products");
    });
};
