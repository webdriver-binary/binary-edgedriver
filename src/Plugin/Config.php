<?php

declare(strict_types=1);

namespace WebdriverBinary\EdgeDriver\Plugin;

use WebdriverBinary\WebDriverBinaryDownloader\Interfaces\PlatformAnalyserInterface as Platform;

class Config implements \WebdriverBinary\WebDriverBinaryDownloader\Interfaces\ConfigInterface
{
    /**
     * @var \Composer\Package\PackageInterface
     */
    private $configOwner;

    /**
     * @param \Composer\Package\PackageInterface $configOwner
     */
    public function __construct(
        \Composer\Package\PackageInterface $configOwner
    ) {
        $this->configOwner = $configOwner;
    }

    public function getPreferences(): array
    {
        $extra = $this->configOwner->getExtra();

        $defaults = [
            'version' => null
        ];

        return array_replace(
            $defaults,
            isset($extra['edgedriver']) ? $extra['edgedriver'] : []
        );
    }

    public function getDriverName(): string
    {
        return 'EdgeDriver';
    }

    public function getRequestUrlConfig(): array
    {
        $baseUrl = 'https://download.microsoft.com/download';
        
        return [
            self::REQUEST_VERSION => false,
            self::REQUEST_DOWNLOAD => sprintf('%s/{{hash}}/{{file}}', $baseUrl)
        ];
    }

    public function getBrowserBinaryPaths(): array
    {
        return [
            Platform::TYPE_LINUX32 => [],
            Platform::TYPE_LINUX64 => [],
            Platform::TYPE_MAC64 => [],
            Platform::TYPE_WIN32 => [null],
            Platform::TYPE_WIN64 => [null]
        ];
    }

    public function getBrowserVersionPollingConfig(): array
    {
        return [
            'powershell.exe "Get-AppxPackage Microsoft.MicrosoftEdge | %%{echo $_.version}"' => ['([0-9].+)']
        ];
    }
    
    public function getDriverVersionPollingConfig(): array
    {
        return [
            'wmic datafile where name="%s" get Version /value' => ['Version=([0-9].+)']
        ];
    }
    
    public function getBrowserDriverVersionMap(): array
    {
        return [
            '18.00000' => '',
            '17.17134' => ['6.17134', '10.0.17134.1'],
            '16.16299' => ['5.16299', '10.0.16299.15'],
            '15.15063' => ['4.15063', '10.0.15063.0'],
            '14.14393' => ['3.14393', '10.0.14393.0'],
            '13.10586' => ['2.10586', '10.0.10586.0'],
            '12.10240' => ['1.10240', '10.0.10240.0']
        ];
    }
    
    public function getDriverVersionHashMap(): array
    {
        return [
            '6.17134' => 'F/8/A/F8AF50AB-3C3A-4BC4-8773-DC27B32988DD',
            '5.16299' => 'D/4/1/D417998A-58EE-4EFE-A7CC-39EF9E020768',
            '4.15063' => '3/4/2/342316D7-EBE0-4F10-ABA2-AE8E0CDF36DD',
            '3.14393' => '3/2/D/32D3E464-F2EF-490F-841B-05D53C848D15',
            '2.10586' => 'C/0/7/C07EBF21-5305-4EC8-83B1-A6FCC8F93F45',
            '1.10240' => '8/D/0/8D0D08CF-790D-4586-B726-C6469A9ED49C',
        ];
    }
    
    public function getRemoteFileNames(): array
    {
        return [
            Platform::TYPE_LINUX32 => '',
            Platform::TYPE_LINUX64 => '',
            Platform::TYPE_MAC64 => '',
            Platform::TYPE_WIN32 => 'MicrosoftWebDriver.exe',
            Platform::TYPE_WIN64 => 'MicrosoftWebDriver.exe'
        ];
    }

    public function getExecutableFileNames(): array
    {
        return [
            Platform::TYPE_LINUX32 => '',
            Platform::TYPE_LINUX64 => '', 
            Platform::TYPE_MAC64 => '',
            Platform::TYPE_WIN32 => 'MicrosoftWebDriver.exe',
            Platform::TYPE_WIN64 => 'MicrosoftWebDriver.exe'
        ];
    }

    public function getExecutableFileRenames(): array
    {
        return [
            'MicrosoftWebDriver.exe' => 'edgedriver.exe'
        ];
    }
}
