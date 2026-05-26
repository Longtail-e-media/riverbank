<link href="<?php echo ASSETS_PATH; ?>uploadify/uploadify.css" rel="stylesheet" type="text/css"/>
<?php
$moduleTablename = "tbl_schemas"; // Database table name
$moduleId = 30;                // module id >>>>> tbl_modules

if (isset($_GET['page']) && $_GET['page'] == "schema" && isset($_GET['mode']) && $_GET['mode'] == "list"):
    ?>
    <h3>
        List Schema
        <?php if ($session->get('u_id') == 2) { ?>
            <a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);"
               onClick="addNewSchema();">
                <span class="glyph-icon icon-separator"><i class="glyph-icon icon-plus-square"></i></span>
                <span class="button-content"> Add New </span>
            </a>
        <?php } ?>
    </h3>
    <div class="my-msg"></div>
    <div class="example-box">
        <div class="example-code">
            <table cellpadding="0" cellspacing="0" border="0" class="table" id="example">
                <thead>
                <tr>
                    <th style="display:none;"></th>
                    <th class="text-center">S. No.</th>
                    <th class="text-center">Title</th>
                    <th class="text-center"><?php echo $GLOBALS['basic']['action']; ?></th>
                </tr>
                </thead>

                <tbody>
                <?php $records = Schema::find_by_sql("SELECT * FROM " . $moduleTablename . " ORDER BY sortorder DESC ");
                foreach ($records as $key => $record): ?>
                    <tr id="<?php echo $record->id; ?>">
                        <td style="display:none;"><?php echo $key + 1; ?></td>
                        <td class="text-center"><?php echo $key + 1; ?></td>
                        <td>
                            <div class="col-md-7">
                                <a href="javascript:void(0);" onClick="editRecord(<?php echo $record->id; ?>);"
                                   class="loadingbar-demo"
                                   title="<?php echo $record->title; ?>"><?php echo $record->title; ?></a>
                            </div>
                        </td>
                        <td class="text-center">
                            <?php
                            $statusImage = ($record->status == 1) ? "bg-green" : "bg-red";
                            $statusText = ($record->status == 1) ? $GLOBALS['basic']['clickUnpub'] : $GLOBALS['basic']['clickPub'];
                            ?>
                            <!--<a href="javascript:void(0);"
                               class="btn small <?php echo $statusImage; ?> tooltip-button statusToggler" data-placement="top" title="<?php echo $statusText; ?>"
                               status="<?php echo $record->status; ?>" id="imgHolder_<?php echo $record->id; ?>" moduleId="<?php echo $record->id; ?>">
                                <i class="glyph-icon icon-flag"></i>
                            </a>-->
                            <a href="javascript:void(0);" class="loadingbar-demo btn small bg-blue-alt tooltip-button"
                               data-placement="top" title="Edit" onclick="editRecord(<?php echo $record->id; ?>);">
                                <i class="glyph-icon icon-edit"></i>
                            </a>
                            <!--<a href="javascript:void(0);" class="btn small bg-red tooltip-button" data-placement="top"
                               title="Remove" onclick="recordDelete(<?php echo $record->id; ?>);">
                                <i class="glyph-icon icon-remove"></i>
                            </a>-->
                            <input name="sortId" type="hidden" value="<?php echo $record->id; ?>">
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php elseif (isset($_GET['mode']) && $_GET['mode'] == "addEdit"):
    if (isset($_GET['id']) && !empty($_GET['id'])):
        $schemaId   = addslashes($_REQUEST['id']);
        $recInfo    = Schema::find_by_id($schemaId);
        $status     = ($recInfo->status == 1) ? "checked" : " ";
        $unstatus   = ($recInfo->status == 0) ? "checked" : " ";
    endif;
    ?>
    <h3>
        <?php echo (isset($_GET['id'])) ? 'Edit Schema' : 'Add Schema'; ?>
        <a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);"
           onClick="viewSchemaList();">
            <span class="glyph-icon icon-separator"><i class="glyph-icon icon-arrow-circle-left"></i></span>
            <span class="button-content"> Back </span>
        </a>
    </h3>

    <div class="my-msg"></div>
    <div class="example-box">
        <div class="example-code">
            <form action="" class="col-md-12 center-margin" id="schema_frm">
                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Title :
                        </label>
                    </div>
                    <div class="form-input col-md-10">
                        <?php $readonly = !empty($recInfo) ? 'readonly' : ''; ?>
                        <input placeholder="Title" class="col-md-6 validate[required,length[0,200]]" type="text"
                               name="title" id="title" <?= $readonly ?>
                               value="<?php echo !empty($recInfo->title) ? $recInfo->title : ""; ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Schema Code :
                        </label>
                    </div>
                    <div class="form-input col-md-6">
                        <?php $siteRegular = Config::find_by_id(1);
                        $schema_placeholder = '<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@graph": [
        {
            "@type": "WebSite",
            "@id": "' . BASE_URL . '#website",
            "url": "' . BASE_URL . '",
            "name": "' . $siteRegular->sitetitle . '"
        },
        {
            "@type": "Hotel",
            "@id": "' . BASE_URL . '#hotel",
            "name": "' . $siteRegular->sitetitle . '",
            "url": "' . BASE_URL . '",
            "logo": "' . BASE_URL . 'images/preference/mBlyc-logo.webp",
        },
        {
            "@type": "WebPage",
            "@id": "' . BASE_URL . '#webpage",
            "name": "' . $siteRegular->sitetitle . '",
            "description": "' . $siteRegular->site_description . '",
            "url": "' . BASE_URL . '/clubhimalaya/"
        }
    ]
}
</script>';?>
                        <textarea placeholder="<?= htmlentities($schema_placeholder) ?>" name="schema_code" id="schema_code"
                                  class="large-textarea"><?php echo !empty($recInfo->schema_code) ? $recInfo->schema_code : ""; ?></textarea>
                    </div>
                </div>

                <!-- ======== FAQ Schema Builder ======== -->
                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label>FAQ Schema :</label>
                    </div>
                    <div class="col-md-10">
                        <div id="faq-fields-container" style="display:flex;flex-wrap:wrap;"></div>
                        <a href="javascript:void(0);"
                           class="btn medium bg-blue tooltip-button"
                           style="margin-top:6px;"
                           title="Add FAQ row"
                           onclick="addFaqRow();">
                            <i class="glyph-icon icon-plus-square mrg10R"></i>
                            <span class="button-content"> Add FAQ </span>
                        </a>
                    </div>
                </div>
                <?php
                // Output existing FAQ data as a safe JS literal so the builder can pre-populate rows.
                // json_encode with JSON_HEX_TAG prevents </script> injection.
                $_faqRaw = isset($recInfo->faq_schema) ? trim((string)$recInfo->faq_schema) : '';
                if ($_faqRaw !== '') {
                    $_faqArr = json_decode($_faqRaw, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($_faqArr) && count($_faqArr) > 0) {
                        echo '<script>window._existingFaqData='
                            . json_encode($_faqArr, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_UNESCAPED_SLASHES)
                            . ';</script>' . "\n";
                    }
                }
                ?>
                <!-- ======== end FAQ Schema Builder ======== -->

                <button btn-action='0' type="submit" name="submit" id="btn-submit" title="Save"
                        class="btn-submit btn large primary-bg text-transform-upr font-bold font-size-11 radius-all-4">
                    <span class="button-content">Save</span>
                </button>
                <input myaction='0' type="hidden" name="idValue" id="idValue" value="<?php echo !empty($recInfo->id) ? $recInfo->id : 0; ?>"/>
            </form>
        </div>
    </div>
<?php endif; ?>