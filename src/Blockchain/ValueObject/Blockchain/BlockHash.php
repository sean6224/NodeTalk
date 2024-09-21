<?php
declare(strict_types=1);
namespace App\Blockchain\ValueObject\Blockchain;

use App\Shared\ValueObject\StringValue;

/**
 * Class BlockHash
 *
 * Represents the hash of a block in the blockchain.
 * Inherits from StringValue to ensure that it is treated as a string value object.
 */
final class BlockHash extends StringValue
{

}
