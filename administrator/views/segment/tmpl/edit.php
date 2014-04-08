<?php
/**
 * @version     1.0.0
 * @package     com_threerains_pushnotifications
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      3rains <info@3rains.net> - http://3rains.net
 */
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_threerains_pushnotifications/assets/css/threerains_pushnotifications.css');
?>
<script type="text/javascript">
    js = jQuery.noConflict();
    js(document).ready(function() {
        
	js('input:hidden.filter_os').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('filter_oshidden')){
			js('#jform_filter_os option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_filter_os").trigger("liszt:updated");
	js('input:hidden.filter_devices').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('filter_deviceshidden')){
			js('#jform_filter_devices option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_filter_devices").trigger("liszt:updated");
	js('input:hidden.filter_list').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('filter_listhidden')){
			js('#jform_filter_list option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_filter_list").trigger("liszt:updated");
	js('input:hidden.filter_language').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('filter_languagehidden')){
			js('#jform_filter_language option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_filter_language").trigger("liszt:updated");
    });

    Joomla.submitbutton = function(task)
    {
        if (task == 'segment.cancel') {
            Joomla.submitform(task, document.getElementById('segment-form'));
        }
        else {
            
            if (task != 'segment.cancel' && document.formvalidator.isValid(document.id('segment-form'))) {
                
	if(js('#jform_filter_os option:selected').length == 0){
		js("#jform_filter_os option[value=0]").attr('selected','selected');
	}
	if(js('#jform_filter_devices option:selected').length == 0){
		js("#jform_filter_devices option[value=0]").attr('selected','selected');
	}
	if(js('#jform_filter_list option:selected').length == 0){
		js("#jform_filter_list option[value=0]").attr('selected','selected');
	}
	if(js('#jform_filter_language option:selected').length == 0){
		js("#jform_filter_language option[value=0]").attr('selected','selected');
	}
                Joomla.submitform(task, document.getElementById('segment-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_threerains_pushnotifications&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="segment-form" class="form-validate">

    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_TITLE_SEGMENT', true)); ?>
        <div class="row-fluid">
            <div class="span10 form-horizontal">
                <fieldset class="adminform">

                    				<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
				<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('state'); ?></div>
			</div>
				<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
				<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

				<?php if(empty($this->item->created_by)){ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />

				<?php } 
				else{ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />

				<?php } ?>			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('filter_os'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('filter_os'); ?></div>
			</div>

			<?php
				foreach((array)$this->item->filter_os as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="filter_os" name="jform[filter_oshidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('filter_devices'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('filter_devices'); ?></div>
			</div>

			<?php
				foreach((array)$this->item->filter_devices as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="filter_devices" name="jform[filter_deviceshidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('filter_list'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('filter_list'); ?></div>
			</div>

			<?php
				foreach((array)$this->item->filter_list as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="filter_list" name="jform[filter_listhidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('filter_language'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('filter_language'); ?></div>
			</div>

			<?php
				foreach((array)$this->item->filter_language as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="filter_language" name="jform[filter_languagehidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('filter_appunused'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('filter_appunused'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('name'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('name'); ?></div>
			</div>


                </fieldset>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        
        <?php if (JFactory::getUser()->authorise('core.admin','threerains_pushnotifications')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_FIELDSET_RULES', true)); ?>
		<?php echo $this->form->getInput('rules'); ?>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
<?php endif; ?>

        <?php echo JHtml::_('bootstrap.endTabSet'); ?>

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>

    </div>
</form>