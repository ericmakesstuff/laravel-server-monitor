<?php

namespace EricMakesStuff\ServerMonitor\Monitors;

use Carbon\Carbon;
use EricMakesStuff\ServerMonitor\Events\DiskUsageAlarm;
use EricMakesStuff\ServerMonitor\Events\DiskUsageHealthy;
use EricMakesStuff\ServerMonitor\Exceptions\InvalidPath;
use EricMakesStuff\ServerMonitor\Helpers\Format;

class DiskUsageMonitor extends BaseMonitor
{
    /**  @var int */
    protected $totalSpace;

    /**  @var int */
    protected $freeSpace;

    /**  @var int */
    protected $usedSpace;

    /**  @var float */
    protected $percentageUsed;

    /** @var string */
    protected $path;

    /** @var int */
    protected $alarmPercentage = 75;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->path = __DIR__;

        if (!empty($config['path'])) {
            $this->path = $config['path'];
        }

        if (!empty($config['alarmPercentage'])) {
            $this->alarmPercentage = intval($config['alarmPercentage']);
        }
    }

    /**
     * @throws InvalidPath
     */
    public function runMonitor()
    {
        if (! file_exists($this->path)) {
            throw InvalidPath::pathDoesNotExist($this->path);
        }

        $this->totalSpace = disk_total_space($this->path);

        $this->freeSpace = disk_free_space($this->path);
        
        $this->usedSpace = $this->totalSpace - $this->freeSpace;

        $this->percentageUsed = sprintf('%.2f',($this->usedSpace / $this->totalSpace) * 100);
        
        if ($this->percentageUsed >= $this->alarmPercentage) {
            event(new DiskUsageAlarm($this));
        } else {
            event(new DiskUsageHealthy($this));
        }
    }

    /**
     * @return string
     */
    public function getTotalSpace()
    {
        return Format::getHumanReadableSize($this->totalSpace);
    }

    /**
     * @return string
     */
    public function getFreeSpace()
    {
        return Format::getHumanReadableSize($this->freeSpace);
    }

    /**
     * @return string
     */
    public function getUsedSpace()
    {
        return Format::getHumanReadableSize($this->usedSpace);
    }

    /**
     * @return string
     */
    public function getPercentageUsed()
    {
        return $this->percentageUsed . '%';
    }

    /**
     * @return mixed|string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getAlarmPercentage()
    {
        return $this->alarmPercentage . '%';
    }
}
