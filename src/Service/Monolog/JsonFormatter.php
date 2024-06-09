<?php

namespace App\Service\Monolog;

use DateTimeInterface;
use JsonSerializable;
use Monolog\Formatter\JsonFormatter as MonologJsonFormatter;
use Monolog\LogRecord;
use Throwable;
use function count;
use function get_resource_type;
use function is_array;
use function is_object;
use function is_resource;
use function is_string;
use function json_decode;
use function mb_strimwidth;
use function sprintf;

/**
 * Class JsonFormatter.
 */
class JsonFormatter extends MonologJsonFormatter
{
    protected int $maxNormalizeDepth = 9;

    protected int $maxNormalizeItemCount = 1000;

    /**
     * JsonFormatter constructor.
     *
     * @param self::BATCH_MODE_* $batchMode
     */
    public function __construct(
        int $batchMode = self::BATCH_MODE_JSON,
        bool $appendNewline = true,
        ?string $dateFormat = null
    ) {
        parent::__construct($batchMode, $appendNewline);

        $this->dateFormat = $dateFormat ?? 'Y-m-d\TH:i:s.uP';
    }

    /**
     * {@inheritdoc}
     */
    public function format(LogRecord $record): string
    {
        $record = $record->toArray();

        if (($record['datetime'] ?? null) instanceof DateTimeInterface) {
            $record['datetime'] = $record['datetime']->format($this->dateFormat);
        }

        $data = [
            'datetime' => $record['datetime'] ?? null,
            'level' => $record['level_name'] ?? null,
            'guid' => $record['guid'] ?? null,
            'type' => $record['channel'] ?? null,
            'message' => mb_strimwidth((string) ($record['message'] ?? null), 0, 1000, '...'),
            'scope' => $this->getNormalizeData($record, 'context'),
        ];

        return $this->toJson($data, true) . ($this->appendNewline ? "\n" : '');
    }

    /**
     * {@inheritdoc}
     */
    public function formatBatchJson(array $records): string
    {
        $result = [];

        foreach ($records as $record) {
            $result[] = json_decode($this->format($record), true);
        }

        return $this->toJson($result, true);
    }

    protected function getNormalizeData(array $record, string $key): ?string
    {
        $result = null;

        if (!empty($record[$key])) {
            $normalizeData = $this->normalizeEtp($record[$key]);

            if (!empty($normalizeData)) {
                if (!is_string($normalizeData)) {
                    $normalizeData = $this->toJson($normalizeData, true);
                }

                $result = mb_strimwidth($normalizeData, 0, 2000, '...');
            }
        }

        return $result;
    }

    /**
     * Normalizes given $data.
     *
     * @param mixed $data
     * @param int $depth
     * @return mixed
     */
    protected function normalizeEtp($data, int $depth = 0): mixed
    {
        switch (true) {
            case $depth > $this->maxNormalizeDepth:
                $result = sprintf('Over %d levels deep, aborting normalization', $this->maxNormalizeDepth);

                break;
            case is_array($data):
                $result = $this->normalizeArray($data, $depth);

                break;
            case $data instanceof Throwable:
                $result = $this->normalizeException($data, $depth);

                break;
            case $data instanceof JsonSerializable:
                $result = $this->normalizeArray((array) $data->jsonSerialize(), $depth);

                break;
            case is_object($data) && method_exists($data, '__toString'):
                $result = (string) $data;

                break;
            case is_resource($data):
                $result = sprintf('[resource(%s)]', get_resource_type($data));

                break;
            default:
                $result = $data;
        }

        return $result;
    }

    protected function normalizeArray(array $data, int $depth = 0): ?array
    {
        $normalized = null;
        $count = 1;

        foreach ($data as $key => $value) {
            if ($count++ > $this->maxNormalizeItemCount) {
                $normalized['...'] = sprintf('Over %d items (%s total), aborting normalization', $this->maxNormalizeItemCount, count($data));

                break;
            }

            $normalized[$key] = $this->normalizeEtp($value, $depth + 1);
        }

        return $normalized;
    }
}
