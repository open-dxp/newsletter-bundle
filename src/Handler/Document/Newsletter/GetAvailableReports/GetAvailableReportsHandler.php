<?php
declare(strict_types=1);

/**
 * OpenDXP
 *
 * This source file is licensed under the GNU General Public License version 3 (GPLv3).
 *
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) Pimcore GmbH (https://pimcore.com)
 * @copyright  Modification Copyright (c) OpenDXP (https://www.opendxp.io)
 * @license    https://www.gnu.org/licenses/gpl-3.0.html  GNU General Public License version 3 (GPLv3)
 */

namespace OpenDxp\Bundle\NewsletterBundle\Handler\Document\Newsletter\GetAvailableReports;

use OpenDxp\Bundle\CustomReportsBundle\Tool\Config;

/**
 * @internal
 */
final class GetAvailableReportsHandler
{
    public function __invoke(GetAvailableReportsPayload $payload): GetAvailableReportsResult
    {
        return new GetAvailableReportsResult(match ($payload->task) {
            'list' => $this->reportList(),
            'fieldNames' => $this->fieldNames($payload->reportId),
            default => [],
        });
    }

    private function reportList(): array
    {
        $reports = [];
        foreach (Config::getReportsList() as $report) {
            $reports[] = ['id' => $report['id'], 'text' => $report['text']];
        }

        return $reports;
    }

    private function fieldNames(string $reportId): array
    {
        $report = Config::getByName($reportId);
        $columnConfiguration = $report?->getColumnConfiguration() ?? [];

        $columns = [];
        foreach ($columnConfiguration as $column) {
            if ($column['display']) {
                $columns[] = ['name' => $column['name']];
            }
        }

        return $columns;
    }
}
