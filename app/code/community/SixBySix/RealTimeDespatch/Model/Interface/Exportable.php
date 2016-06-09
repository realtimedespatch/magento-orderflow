<?php

/**
 * Exportable Interface.
 */
interface SixBySix_RealTimeDespatch_Model_Interface_Exportable
{
    /**
     * Applies bespoke logic to confirm an export.
     *
     * @return mixed
     */
    public function export(\DateTime $exportedAt = null);

    /**
     * Returns the export reference.
     *
     * @return mixed
     */
    public function getExportReference();

    /**
     * Returns the export type.
     *
     * @return mixed
     */
    public function getExportType();
}