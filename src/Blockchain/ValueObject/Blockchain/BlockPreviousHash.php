<?php
declare(strict_types=1);
namespace App\Blockchain\ValueObject\Blockchain;

use App\Shared\ValueObject\StringValue;

/**
 * Class BlockPreviousHash
 *
 * Represents the hash of the previous block in the blockchain.
 * Inherits from StringValue to ensure that it is treated as a string value object.
 */
final class BlockPreviousHash extends StringValue
{

}
