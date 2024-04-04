<script type="text/javascript">
$(document).ready(function() {

    $('input[name="is_distributer"]').click(function() {
        if ($(this).is(":checked")) {
            $("#switch_under_distributer").hide();
            $("#switch_under_admin").hide();
            $("#switch_supervisor").hide();
            $("#switch_company").hide();
            $("#switch_master").hide();
            $("#switch_com").hide();
        } else {
            $("#switch_under_distributer").show();
            $("#switch_distributer").show();
            $("#switch_under_admin").show();
            $("#switch_supervisor").show();
            $("#switch_company").show();
            $("#switch_master").show();
            $("#switch_com").show();
        }

    });

    $('input[name="is_under_distributer"]').click(function() {
        if ($(this).is(":checked")) {
            $("#switch_distributer").hide();
            $("#switch_under_admin").hide();
            $("#switch_supervisor").hide();
            $("#switch_company").hide();
            $("#switch_master").hide();
            $("#switch_com").hide();

        } else {
            $("#switch_under_distributer").show();
            $("#switch_distributer").show();
            $("#switch_under_admin").show();
            $("#switch_supervisor").show();
            $("#switch_company").show();
            $("#switch_master").show();
            $("#switch_com").show();
        }

    });

    $('input[name="is_admin"]').click(function() {
        if ($(this).is(":checked")) {
            $("#switch_distributer").hide();
            $("#switch_under_distributer").hide();
            $("#switch_supervisor").hide();
            $("#switch_company").hide();
            $("#switch_master").hide();
            $("#switch_com").hide();
        } else {
            $("#switch_under_distributer").show();
            $("#switch_distributer").show();
            $("#switch_under_admin").show();
            $("#switch_supervisor").show();
            $("#switch_company").show();
            $("#switch_master").show();
            $("#switch_com").show();
        }

    });

    $('input[name="is_supervisor"]').click(function() {
        if ($(this).is(":checked")) {
            $("#switch_distributer").hide();
            $("#switch_under_distributer").hide();
            $("#switch_under_admin").hide();
            $("#switch_company").hide();
            $("#switch_master").hide();
            $("#switch_com").hide();
        } else {
            $("#switch_under_distributer").show();
            $("#switch_distributer").show();
            $("#switch_under_admin").show();
            $("#switch_supervisor").show();
            $("#switch_company").show();
            $("#switch_master").show();
            $("#switch_com").show();
        }

    });

    $('input[name="is_company"]').click(function() {
        if ($(this).is(":checked")) {
            $("#switch_distributer").hide();
            $("#switch_under_distributer").hide();
            $("#switch_under_admin").hide();
            $("#switch_supervisor").hide();
            $("#switch_master").hide();
            $("#switch_com").hide();
        } else {
            $("#switch_company").show();
            $("#switch_under_distributer").show();
            $("#switch_distributer").show();
            $("#switch_under_admin").show();
            $("#switch_supervisor").show();
            $("#switch_master").show();
            $("#switch_com").show();
        }

    });

    $('input[name="is_master"]').click(function() {
        if ($(this).is(":checked")) {
            $("#switch_distributer").hide();
            $("#switch_under_distributer").hide();
            $("#switch_under_admin").hide();
            $("#switch_supervisor").hide();
            $("#switch_com").hide();
            $("#switch_company").hide();
        } else {
            $("#switch_company").show();
            $("#switch_under_distributer").show();
            $("#switch_distributer").show();
            $("#switch_under_admin").show();
            $("#switch_supervisor").show();
            $("#switch_com").show();
        }

    });

    $('input[name="is_com"]').click(function() {
        if ($(this).is(":checked")) {
            $("#switch_distributer").hide();
            $("#switch_under_distributer").hide();
            $("#switch_under_admin").hide();
            $("#switch_supervisor").hide();
            $("#switch_master").hide();
            $("#switch_company").hide();
        } else {
            $("#switch_company").show();
            $("#switch_master").show();
            $("#switch_under_distributer").show();
            $("#switch_distributer").show();
            $("#switch_under_admin").show();
            $("#switch_supervisor").show();
        }

    });

});
</script> 