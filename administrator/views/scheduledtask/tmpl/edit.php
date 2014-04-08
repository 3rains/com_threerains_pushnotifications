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
        
	js('input:hidden.segmentid').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('segmentidhidden')){
			js('#jform_segmentid option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_segmentid").trigger("liszt:updated");
    });

    Joomla.submitbutton = function(task)
    {
        if (task == 'scheduledtask.cancel') {
            Joomla.submitform(task, document.getElementById('scheduledtask-form'));
        }
        else {
            
            if (task != 'scheduledtask.cancel' && document.formvalidator.isValid(document.id('scheduledtask-form'))) {
                
	if(js('#jform_segmentid option:selected').length == 0){
		js("#jform_segmentid option[value=0]").attr('selected','selected');
	}
                Joomla.submitform(task, document.getElementById('scheduledtask-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_threerains_pushnotifications&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="scheduledtask-form" class="form-validate">

    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_TITLE_SCHEDULEDTASK', true)); ?>
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
				<div class="control-label"><?php echo $this->form->getLabel('send_date'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('send_date'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('send_hour'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('send_hour'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('send_minute'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('send_minute'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('segmentid'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('segmentid'); ?></div>
			</div>

			<?php
				foreach((array)$this->item->segmentid as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="segmentid" name="jform[segmentidhidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>

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