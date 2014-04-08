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
        
	js('input:hidden.device_id').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('device_idhidden')){
			js('#jform_device_id option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_device_id").trigger("liszt:updated");
	js('input:hidden.list_id').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('list_idhidden')){
			js('#jform_list_id option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_list_id").trigger("liszt:updated");
    });

    Joomla.submitbutton = function(task)
    {
        if (task == 'subscriber.cancel') {
            Joomla.submitform(task, document.getElementById('subscriber-form'));
        }
        else {
            
            if (task != 'subscriber.cancel' && document.formvalidator.isValid(document.id('subscriber-form'))) {
                
                Joomla.submitform(task, document.getElementById('subscriber-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_threerains_pushnotifications&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="subscriber-form" class="form-validate">

    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_TITLE_SUBSCRIBER', true)); ?>
        <div class="row-fluid">
            <div class="span10 form-horizontal">
                <fieldset class="adminform">

                    				<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
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
				<div class="control-label"><?php echo $this->form->getLabel('device_id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('device_id'); ?></div>
			</div>

			<?php
				foreach((array)$this->item->device_id as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="device_id" name="jform[device_idhidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('list_id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('list_id'); ?></div>
			</div>

			<?php
				foreach((array)$this->item->list_id as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="list_id" name="jform[list_idhidden]['.$value.']" value="'.$value.'" />';
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