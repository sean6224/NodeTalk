<?php
declare(strict_types=1);
namespace App\Blockchain\Interfaces\MerkleTree;

use App\Blockchain\Core\MerkleTree\MerkleNode;

/**
 * Interface MerkleNodeInterface
 *
 * This interface defines the essential methods required to manage
 * a node in a Merkle Tree. Each node may have left and right child nodes,
 * and the hash of the node is either pre-calculated or derived from its children.
 *
 * Implementing classes should provide efficient ways to calculate,
 * cache, and retrieve the node's hash and its children.
 *
 * @package App\Blockchain\Interfaces\MerkleTree
 */
interface MerkleNodeInterface
{
    /**
     * Efficiently returns the hash of this node.
     *
     * @return string The cached or recalculated hash.
     */
    public function getHash(): string;

    /**
     * Returns if the node is a leaf node (no children).
     *
     * @return bool True if it is a leaf node, false otherwise.
     */
    public function isLeaf(): bool;

    /**
     * Sets the left child node and invalidates the hash.
     *
     * @param MerkleNode|null $left Left child node.
     * @return void
     */
    public function setLeft(?MerkleNode $left): void;

    /**
     * Sets the right child node and invalidates the hash.
     *
     * @param MerkleNode|null $right Right child node.
     * @return void
     */
    public function setRight(?MerkleNode $right): void;

    /**
     * Gets the left child node.
     *
     * @return MerkleNode|null The left child node, or null if it doesn't exist.
     */
    public function getLeft(): ?MerkleNode;

    /**
     * Gets the right child node.
     *
     * @return MerkleNode|null The right child node, or null if it doesn't exist.
     */
    public function getRight(): ?MerkleNode;

    /**
     * Converts the node to an array.
     *
     * @return array Node as an array structure.
     */
    public function toArray(): array;

    /**
     * Returns a string representation of the node.
     *
     * @return string Node as a string.
     */
    public function __toString(): string;
}
