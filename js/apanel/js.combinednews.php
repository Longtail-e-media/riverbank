<script language="javascript">

    function getLocation() {
        return '<?php echo BASE_URL;?>includes/controllers/ajax.combinednews.php';
    }

    function getTableId() {
        return 'table_dnd';
    }

    $(document).ready(function () {
        oTable = $('#example').dataTable({
            "bJQueryUI": true,
            "sPaginationType": "full_numbers"
        }).rowReordering({
            sURL: "<?php echo BASE_URL;?>includes/controllers/ajax.combinednews.php?action=sort",
            fnSuccess: function (message) {
                var msg = jQuery.parseJSON(message);
                showMessage(msg.action, msg.message);
            }
        });
    });

    $(document).ready(function () {
        oTable = $('#subexample').dataTable({
            "bJQueryUI": true,
            "sPaginationType": "full_numbers"
        }).rowReordering({
            sURL: "<?php echo BASE_URL;?>includes/controllers/ajax.combinednews.php?action=subSort",
            fnSuccess: function (message) {
                var msg = jQuery.parseJSON(message);
                showMessage(msg.action, msg.message);
            }
        });
    });

    /***************************************** News Create Date *******************************************/

    $(document).ready(function () {

        $.datepicker.setDefaults({dateFormat: 'yy-mm-dd', firstDay: 1});
        $('#event_stdate').datepicker({
            minDate: 0, changeMonth: true, changeYear: true, onSelect: function (selectedDate) {
                var minDate = $(this).datepicker('getDate');
                if (minDate) {
                    minDate.setDate(minDate.getDate() + 1);
                }
                $('#event_endate').datepicker('option', 'minDate', minDate || 1); // Date + 1 or tomorrow by default
            }
        });
        $('#event_endate').datepicker({
            minDate: 1, changeMonth: true, changeYear: true, onSelect: function (selectedDate) {
                var maxDate = $(this).datepicker('getDate');
                if (maxDate) {
                    maxDate.setDate(maxDate.getDate() - 1);
                }
                $('#event_stdate').datepicker('option', 'maxDate', maxDate); // Date - 1
            }
        });
    });

    /*************************************** Toggle AddEdit Form ********************************************/
    function toggleMetadata() {
        $(".metadata").slideToggle("slow", function () {
        });
    }

    $(document).ready(function () {
        $('.btn-submit').on('click', function () {
            var actVal = $(this).attr('btn-action');
            $('#idValue').attr('myaction', actVal);
        })

        // form submisstion actions
        jQuery('#combined_frm').validationEngine({
            autoHidePrompt: true,
            scroll: true,
            onValidationComplete: function (form, status) {
                if (status == true) {
                    $('.btn-submit').attr('disabled', 'true');
                    var action = ($('#idValue').val() == 0) ? "action=add&" : "action=edit&";
                    for (instance in CKEDITOR.instances)
                        CKEDITOR.instances[instance].updateElement();

                    var data = $('#combined_frm').serialize();
                    queryString = action + data;
                    $.ajax({
                        type: "POST",
                        dataType: "JSON",
                        url: getLocation(),
                        data: queryString,
                        success: function (data) {
                            var msg = eval(data);
                            if (msg.action == 'warning') {
                                showMessage(msg.action, msg.message);
                                $('.btn-submit').removeAttr('disabled');
                                $('.formButtons').show();
                                return false
                            }
                            if (msg.action == 'success') {
                                showMessage(msg.action, msg.message);
                                var actionId = $('#idValue').attr('myaction');
                                if (actionId == 2)
                                    setTimeout(function () {
                                        window.location.href = "<?php echo ADMIN_URL?>combinednews/list";
                                    }, 3000);
                                if (actionId == 1)
                                    setTimeout(function () {
                                        window.location.href = "<?php echo ADMIN_URL?>combinednews/addEdit";
                                    }, 3000);
                                if (actionId == 0)
                                    setTimeout(function () {
                                        window.location.href = "";
                                    }, 3000);
                            }
                            if (msg.action == 'notice') {
                                showMessage(msg.action, msg.message);
                                setTimeout(function () {
                                    window.location.href = window.location.href;
                                }, 3000);
                            }
                            if (msg.action == 'error') {
                                showMessage(msg.action, msg.message);
                                $('#buttonsP img').remove();
                                $('.formButtons').show();
                                return false;
                            }
                        }
                    });
                    return false;
                }
            }
        })

    });

    // Edit records
    function editRecord(Re) {
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: getLocation(),
            data: 'action=editExistsRecord&id=' + Re,
            success: function (data) {
                var msg = eval(data);
                $("#title").val(msg.title);
                $("#idValue").val(msg.editId);
            }
        });
    }

    /*************************************** Toggle AddEdit Form ********************************************/
    function toggleAddEdit() {
        $(".addEdit").slideToggle("slow", function () {
            var icval = $("#iconcols").attr("icoval");
            newicval = (icval == 1) ? 0 : 1;
            $('#iconcols').attr({'icoval': newicval});
            if (icval == 1) {
                $("#iconcols").removeClass('icon-plus-square');
                $("#iconcols").addClass('icon-minus-square');
                $(".newtext").html('Cancel');
            } else {
                $("#iconcols").removeClass('icon-minus-square');
                $("#iconcols").addClass('icon-plus-square');
                $(".newtext").html('Add New');
                $('#combined_frm')[0].reset();
            }

        });
    }

    // Deleting Record
    function recordDelete(Re) {
        $('.MsgTitle').html('<?php echo sprintf($GLOBALS['basic']['deleteRecord_'], "news")?>');
        $('.pText').html('Click on yes button to delete this news permanently.!!');
        $('.divMessageBox').fadeIn();
        $('.MessageBoxContainer').fadeIn(1000);

        $(".botTempo").on("click", function () {
            var popAct = $(this).attr("id");
            if (popAct == 'yes') {
                $.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: getLocation(),
                    data: 'action=delete&id=' + Re,
                    success: function (data) {
                        var msg = eval(data);
                        showMessage(msg.action, msg.message);
                        $('#' + Re).remove();
                        reStructureList(getTableId());
                    }
                });
            } else {
                Re = null;
            }
            $('.divMessageBox').fadeOut();
            $('.MessageBoxContainer').fadeOut(1000);
        });
    }

    /***************************************** View Newss Lists *******************************************/
    function viewcombinednewslist() {
        window.location.href = "<?php echo ADMIN_URL?>combinednews/list";
    }

    /***************************************** Add New Newss *******************************************/
    function AddCombinedNews() {
        window.location.href = "<?php echo ADMIN_URL?>combinednews/addEdit";
    }

    /***************************************** Edit records *****************************************/
    function editRecord(Re) {
        window.location.href = "<?php echo ADMIN_URL?>combinednews/addEdit/" + Re;
    }

    /******************************** Remove temp upload image ********************************/
    function deleteTempimage(Re) {
        $('#previewUserimage' + Re).fadeOut(1000, function () {
            $('#previewUserimage' + Re).remove();
            // $('#preview_Image').html('<input type="hidden" name="imageArrayname" value="" class="">');
        });
    }

    /******************************** Remove saved advertisment image ********************************/
    function deleteSavedCombinedNewsimage(Re) {
        $('.MsgTitle').html('Do you want to delete the record ?');
        $('.pText').html('Clicking yes will be delete this record permanently. !!!');
        $('.divMessageBox').fadeIn();
        $('.MessageBoxContainer').fadeIn(1000);

        $(".botTempo").on("click", function () {
            var popAct = $(this).attr("id");
            if (popAct == 'yes') {
                $('#removeSavedimg' + Re).fadeOut(1000, function () {
                    $('#removeSavedimg' + Re).remove();
                    $('.uploader').fadeIn(500);
                });
            } else {
                Re = '';
            }
            $('.divMessageBox').fadeOut();
            $('.MessageBoxContainer').fadeOut(1000);
        });
    }

    function deleteSavedCombinedNewsHomeimage(Re) {
        $('.MsgTitle').html('Do you want to delete the image ?');
        $('.pText').html('Clicking yes will be delete this image permanently. !!!');
        $('.divMessageBox').fadeIn();
        $('.MessageBoxContainer').fadeIn(1000);

        $(".botTempo").on("click", function () {
            var popAct = $(this).attr("id");
            if (popAct == 'yes') {
                $('#removeSavedHomeimg' + Re).fadeOut(1000, function () {
                    $('#removeSavedHomeimg' + Re).remove();
                    $('.home_uploader').fadeIn(500);
                });
            } else {
                Re = '';
            }
            $('.divMessageBox').fadeOut();
            $('.MessageBoxContainer').fadeOut(1000);
        });
    }

    function deleteSavedActiimage(Re) {
        $('.MsgTitle').html('Do you want to delete the record ?');
        $('.pText').html('Clicking yes will be delete this record permanently. !!!');
        $('.divMessageBox').fadeIn();
        $('.MessageBoxContainer').fadeIn(1000);

        $(".botTempo").on("click", function () {
            var popAct = $(this).attr("id");
            if (popAct == 'yes') {
                $('#removeSavedimg' + Re).fadeOut(1000, function () {
                    $('#removeSavedimg' + Re).remove();
                });
            } else {
                Re = '';
            }
            $('.divMessageBox').fadeOut();
            $('.MessageBoxContainer').fadeOut(1000);
        });
    }

    function deleteSavedgalimage(Re) {
        $('.MsgTitle').html('<?php echo sprintf($GLOBALS['basic']['deleteRecord_'], "image")?>');
        $('.pText').html('Click on yes button to delete this image permanently.!!');
        $('.divMessageBox').fadeIn();
        $('.MessageBoxContainer').fadeIn(1000);

        $(".botTempo").on("click", function () {
            var popAct = $(this).attr("id");
            if (popAct == 'yes') {
                $('#removeSavedimg' + Re).fadeOut(1000, function () {
                    $('#removeSavedimg' + Re).remove();
                    // $('.uploader').fadeIn(500);
                });
            } else {
                Re = '';
            }
            $('.divMessageBox').fadeOut();
            $('.MessageBoxContainer').fadeOut(1000);
        });
    }


    /******************************** Choose Video link or Image ********************************/
    $(document).ready(function () {
        $('.addtype').on('click', function () {
            var clkVal = $(this).val();
            if (clkVal == 1) {
                $('.videolink').slideUp();
                $('.add-image').slideDown();
            } else {
                $('.add-image').slideUp();
                $('.videolink').slideDown();

            }
        })
    })

    /***************************************** Link Type Choose *******************************************/
    function linkTypeSelect(Re) {
        if (Re == 0) {
            $('#linkPage_chosen').removeClass("hide");
            ($('#linksrc').val() == 'http://www.') ? $('#linksrc').val('') : null;
        } else {
            $('#linkPage_chosen').addClass("hide");
            ($('#linksrc').val() == '') ? $('#linksrc').val("http://www.") : null;
        }
    }

    $(document).ready(function () {
        $('#linkPage').change(function () {
            $('#linksrc').val($(this).val());
        });
    });


    $(function () {
        /*************************************** USer Video Status Toggler ******************************************/
        $('.videoStatusToggle').on('click', function () {
            var Re = $(this).attr('rowId');
            var status = $(this).attr('status');
            newStatus = (status == 1) ? 0 : 1;
            $.ajax({
                type: "POST",
                url: getLocation(),
                data: "action=toggleStatus&id=" + Re,
                success: function (msg) {
                }
            });
            $(this).attr({'status': newStatus});
            if (status == 1) {
                $('#toggleImg' + Re).removeClass("icon-check-circle-o");
                $('#toggleImg' + Re).addClass("icon-clock-os-circle-o");
            } else {
                $('#toggleImg' + Re).removeClass("icon-clock-os-circle-o");
                $('#toggleImg' + Re).addClass("icon-check-circle-o");
            }
        });
    });

    function viewCommentlist(Re) {
        window.location.href = "<?php echo ADMIN_URL?>combinednews/commentlist/" + Re;
    }

    /*****************************************/
    function editComment(Re) {
        window.location.href = "<?php echo ADMIN_URL?>combinednews/EditComment/" + Re;
    }


    function subrecordDelete(Re) {
        $('.MsgTitle').html('<?php echo sprintf($GLOBALS['basic']['deleteRecord_'], "Comment")?>');
        $('.pText').html('Click on yes button to delete this comments permanently.!!');
        $('.divMessageBox').fadeIn();
        $('.MessageBoxContainer').fadeIn(1000);

        $(".botTempo").on("click", function () {
            var popAct = $(this).attr("id");
            if (popAct == 'yes') {
                $.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: getLocation(),
                    data: 'action=deleteComment&id=' + Re,
                    success: function (data) {
                        var msg = eval(data);
                        showMessage(msg.action, msg.message);
                        $('#' + Re).remove();
                        reStructureList(getTableId());
                    }
                });
            } else {
                Re = null;
            }
            $('.divMessageBox').fadeOut();
            $('.MessageBoxContainer').fadeOut(1000);
        });
    }

    // ============================================================
    // FAQ Schema Builder
    // ============================================================

    // Module-level counter — gives every card a unique DOM id so deletions
    // are always precise regardless of how many rows exist.
    var _faqRowCounter = 0;

    /**
     * Append a new FAQ card to #faq-fields-container.
     *
     * Layout: each card is 50% wide so two sit side-by-side.
     * Question is stacked above the answer (vertical layout).
     * The container must have display:flex + flex-wrap:wrap (set in PHP view).
     *
     * Called by the "Add FAQ" button and automatically on page load to
     * restore previously saved FAQ data (see $(document).ready below).
     *
     * @param {string} [q]  Pre-fill question (edit/restore mode)
     * @param {string} [a]  Pre-fill answer   (edit/restore mode)
     */
    function addFaqRow(q, a) {
        q = (typeof q === 'string') ? q : '';
        a = (typeof a === 'string') ? a : '';
        var id = ++_faqRowCounter;

        var card =
            // Outer wrapper — 50% width so two cards sit side-by-side per row
            '<div class="faq-row" id="faq-row-' + id + '" '
            + 'style="width:50%;box-sizing:border-box;padding:5px;">'

            // Inner card with border to visually group Q+A
            + '<div style="border:1px solid #ddd;border-radius:4px;padding:10px;">'

            // Card header: FAQ counter label (left) + delete button (right)
            + '<div style="display:flex;justify-content:space-between;'
            + 'align-items:center;margin-bottom:8px;">'
            + '<small style="color:#999;font-weight:bold;">FAQ #' + id + '</small>'
            + '<a href="javascript:void(0);" '
            +    'class="btn medium bg-red tooltip-button pad5L pad5R" '
            +    'title="Remove this FAQ" '
            +    'onclick="deleteFaqRow(' + id + ');">'
            + '<i class="glyph-icon icon-minus-square mrg10R"></i>Remove'
            + '</a>'
            + '</div>'

            // Question input — full width, above the answer
            + '<input type="text" name="faq_questions[]" placeholder="Question" '
            + 'style="width:100%;box-sizing:border-box;margin-bottom:6px;" '
            + 'value="' + _faqAttr(q) + '">'

            // Answer textarea — full width, below the question
            + '<textarea name="faq_answers[]" placeholder="Answer" rows="3" '
            + 'style="width:100%;box-sizing:border-box;resize:vertical;">'
            + _faqHtml(a)
            + '</textarea>'

            + '</div>'
            + '</div>';

        jQuery('#faq-fields-container').append(card);
    }

    /**
     * Fade out and remove a FAQ card by its generated ID.
     * @param {number} id  The value of _faqRowCounter at the time the card was created
     */
    function deleteFaqRow(id) {
        jQuery('#faq-row-' + id).fadeOut(180, function () { jQuery(this).remove(); });
    }

    /* --- DOM-string escaping helpers — never trust external data --- */

    /** Escape a value for safe use inside an HTML attribute (e.g. value="…"). */
    function _faqAttr(s) {
        return String(s)
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }

    /** Escape a value for safe insertion as textarea text content. */
    function _faqHtml(s) {
        return String(s)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }

    /**
     * On DOM ready, restore any previously saved FAQ rows.
     *
     * window._existingFaqData is written as an inline <script> by the PHP view
     * immediately after #faq-fields-container, so it is always defined before
     * this $(document).ready handler runs.
     */
    jQuery(document).ready(function () {
        if (typeof window._existingFaqData !== 'undefined'
            && Array.isArray(window._existingFaqData)) {
            jQuery.each(window._existingFaqData, function (i, item) {
                if (item && typeof item.q === 'string' && typeof item.a === 'string') {
                    addFaqRow(item.q, item.a);
                }
            });
        }
    });

    // ============================================================
    // end FAQ Schema Builder
    // ============================================================
</script>