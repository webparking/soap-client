<?php
/**
 *
 *
 *  NOTICE OF LICENSE
 *
 *  This source file is subject to the Open Software License (OSL 3.0)
 *  that is provided with Magento in the file LICENSE.txt.
 *  It is also available through the world-wide-web at this URL:
 *  http://opensource.org/licenses/osl-3.0.php
 *
 *  DISCLAIMER
 *
 *  Do not edit or add to this file if you wish to upgrade the DistriMediaClient plugin
 *  to newer versions in the future. If you wish to customize the plugin for your
 *  needs please document your changes and make backups before your update.
 *
 *  @category  Baldwin
 *  @package  DistriMediaClient
 *  @author      Tristan Hofman <info@baldwin.be>
 *  @copyright Copyright (c) 2020 Baldwin BV (https://www.baldwin.be)
 *  @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
 *  INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
 *  PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 *  HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN
 *  ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 *  WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 */

declare(strict_types=1);

namespace DistriMedia\SoapClientTest\Service;

use DistriMedia\SoapClient\Service\Inventory as InventoryService;
use DistriMedia\SoapClient\Struct\Response\Inventory;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Client\ClientInterface;

class InventoryTest extends TestCase
{
    /**
     * @return InventoryService
     */
    private function createInventoryService(ClientInterface $client = null): InventoryService
    {
        $uri = $_ENV['API_URI'] ?: '';
        $webshopCode = $_ENV['WEBSHOP_CODE'] ?: '';
        $password = $_ENV['API_PASSWORD'] ?: '';

        return new InventoryService($uri, $webshopCode, $password, client: $client);
    }

    /**
     * @covers ::OrderService
     */
    public function testCreateService()
    {
        $inventorService = $this->createInventoryService();

        self::assertInstanceOf(\DistriMedia\SoapClient\Service\Inventory::class, $inventorService);
    }

    /**
     * @covers ::OrderService
     */
    public function testFetchAllInventory()
    {
        $mock = new MockHandler([
            new Response(200, [], $this->getFixture('inventory')),
        ]);

        $client = new Client([
            'handler' => HandlerStack::create($mock),
        ]);

        $inventorService = $this->createInventoryService($client);

        $result = $inventorService->fetchTotalInventory();

        self::assertInstanceOf(Inventory::class, $result);

        self::assertCount(3, $result->getInventory());
        self::assertCount(0, $result->getInventory()[0]->getLotStockItems());
        self::assertCount(1, $result->getInventory()[1]->getLotStockItems());
        self::assertCount(2, $result->getInventory()[2]->getLotStockItems());

        $lotItems = $result->getInventory()[2]->getLotStockItems();

        static::assertSame('960', $lotItems[1]->getPieces());
        static::assertSame('211', $lotItems[1]->getOverdue());
        static::assertSame('7', $lotItems[1]->getBlocked());
        static::assertSame('L01/33', $lotItems[1]->getLotNumber());
        static::assertSame('20260131', $lotItems[1]->getDueDate());
        static::assertSame('20260130', $lotItems[1]->getLastPickableDate());
    }
}
