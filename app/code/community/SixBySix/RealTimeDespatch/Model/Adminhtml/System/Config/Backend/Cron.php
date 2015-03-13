<?php

/**
 * Backend Cron Model.
 */
abstract class SixBySix_RealTimeDespatch_Model_Adminhtml_System_Config_Backend_Cron extends Mage_Core_Model_Config_Data
{
    const CRON_REGEX = '/^(((([\*]{1}){1})|((\*\/){0,1}(([0-9]{1}){1}|(([1-5]{1}){1}([0-9]{1}){1}){1}))) ((([\*]{1}){1})|((\*\/){0,1}(([0-9]{1}){1}|(([1]{1}){1}([0-9]{1}){1}){1}|([2]{1}){1}([0-3]{1}){1}))) ((([\*]{1}){1})|((\*\/){0,1}(([1-9]{1}){1}|(([1-2]{1}){1}([0-9]{1}){1}){1}|([3]{1}){1}([0-1]{1}){1}))) ((([\*]{1}){1})|((\*\/){0,1}(([1-9]{1}){1}|(([1-2]{1}){1}([0-9]{1}){1}){1}|([3]{1}){1}([0-1]{1}){1}))|(jan|feb|mar|apr|may|jun|jul|aug|sep|okt|nov|dec)) ((([\*]{1}){1})|((\*\/){0,1}(([0-7]{1}){1}))|(sun|mon|tue|wed|thu|fri|sat)))$/';

    /**
     * {@inheritdoc}
     */
    protected function _afterSave()
    {
        try
        {
            $this->_setCronExpression(
                $this->_parseCronExpression()
            );
        }
        catch (Exception $e)
        {
            throw new Exception(Mage::helper('cron')->__('Unable to save the cron expression.'));
        }
    }

    /**
     * Parses the cron expression.
     *
     * @return string
     */
    protected function _parseCronExpression()
    {
        $cronExpr = $this->getData('groups/'.$this->_getCronJobName().'/fields/cron_expr/value');

        if (preg_match(self::CRON_REGEX, $cronExpr) !== 1) {
            throw new Exception(Mage::helper('cron')->__('Unable to parse the cron expression.'));
        }

        return $cronExpr;
    }

    /**
     * Sets the cron expression.
     *
     * @param string $expr
     */
    protected function _setCronExpression($expr)
    {
        Mage::getModel('core/config_data')
            ->load($this->_getConfigPath(), 'path')
            ->setValue($expr)
            ->setPath($this->_getConfigPath())
            ->save();
    }

    /**
     * Returns the current config path.
     *
     * @return string
     */
    protected function _getConfigPath()
    {
        return 'crontab/jobs/'.$this->_getCronJobName().'/schedule/cron_expr';
    }

    /**
     * Returns the name of the current cron job.
     *
     * @return string
     */
    protected abstract function _getCronJobName();
}