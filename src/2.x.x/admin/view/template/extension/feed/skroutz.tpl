<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-skroutz" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-skroutz" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-language"><?php echo $entry_language; ?></label>
            <div class="col-sm-10">
              <select name="skroutz_language" id="input-status" class="form-control">
                <?php foreach($languages as $language) {?>
                  <option value="<?php echo $language['language_id']; ?>" selected="selected">
                  <?php echo $language['name']; ?>
                  </option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">
              Stock statuses
            </label>
            <label class="col-sm-10">
              Skroutz stock statuses
            </label>
          </div>
          <?php foreach($stock_statuses as $stock_status) { ?>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="skroutz_stock_status">
                <?php echo $stock_status['name']; ?>
              </label>
              <div class="col-sm-10">
                <select name="skroutz_stock_status_<?php echo $stock_status['stock_status_id']; ?>" id="skroutz_stock_status" class="form-control">
                    <option value="1" <?= ${'skroutz_stock_status_' . $stock_status['stock_status_id']} == 1 ? "selected" : ""; ?>><?php echo $text_available; ?></option>
                    <option value="2" <?= ${'skroutz_stock_status_' . $stock_status['stock_status_id']} == 2 ? "selected" : ""; ?>><?php echo $text_1_to_3_days; ?></option>
                    <option value="3" <?= ${'skroutz_stock_status_' . $stock_status['stock_status_id']} == 3 ? "selected" : ""; ?>><?php echo $text_4_to_10_days; ?></option>
                    <option value="4" <?= ${'skroutz_stock_status_' . $stock_status['stock_status_id']} == 4 ? "selected" : ""; ?>><?php echo $text_upon_order; ?></option>
                </select>
              </div>
            </div>
          <?php } ?>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-data-feed"><?php echo $entry_data_feed; ?></label>
            <div class="col-sm-10">
              <textarea rows="5" readonly id="input-data-feed" class="form-control"><?php echo $data_feed; ?></textarea>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="skroutz_status" id="input-status" class="form-control">
                <?php if ($skroutz_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>