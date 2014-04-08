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
        
	js('input:hidden.osid').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('osidhidden')){
			js('#jform_osid option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_osid").trigger("liszt:updated");
	js('input:hidden.language').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('languagehidden')){
			js('#jform_language option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_language").trigger("liszt:updated");
	js('input:hidden.app_version').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('app_versionhidden')){
			js('#jform_app_version option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_app_version").trigger("liszt:updated");
    });

    Joomla.submitbutton = function(task)
    {
        if (task == 'device.cancel') {
            Joomla.submitform(task, document.getElementById('device-form'));
        }
        else {
            
            if (task != 'device.cancel' && document.formvalidator.isValid(document.id('device-form'))) {
                
                Joomla.submitform(task, document.getElementById('device-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_threerains_pushnotifications&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="device-form" class="form-validate">

    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_TITLE_DEVICE', true)); ?>
        <div class="row-fluid">
            <div class="span10 form-horizontal">
                <fieldset class="adminform">

                    				<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
				<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
				<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
				<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
				<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

				<?php if(empty($this->item->created_by)){ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />

				<?php } 
				else{ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />

				<?php } ?>			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('token'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('token'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('osid'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('osid'); ?></div>
			</div>

			<?php
				foreach((array)$this->item->osid as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="osid" name="jform[osidhidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('label'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('label'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('language'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('language'); ?></div>
			</div>

			<?php
				foreach((array)$this->item->language as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="language" name="jform[languagehidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('app_version'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('app_version'); ?></div>
			</div>

			<?php
				foreach((array)$this->item->app_version as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="app_version" name="jform[app_versionhidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('model'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('model'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('udid'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('udid'); ?></div>
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