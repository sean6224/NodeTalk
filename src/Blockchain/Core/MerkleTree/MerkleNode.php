<?php
declare(strict_types=1);
namespace App\Blockchain\Core\MerkleTree;

use App\Blockchain\Cryptography\Hashing;
use App\Blockchain\Interfaces\MerkleTree\MerkleNodeInterface;

/**
 * Represents a node in a Merkle Tree.
 *
 * Each node can have a left and right child, with the hash of the node
 * being derived from the hashes of its children. If the node is a leaf,
 * it can have a pre-calculated hash.
 *
 * The node's hash is lazily calculated and cached for efficiency.
 * When children nodes change, the hash is invalidated and recalculated
 * when requested.
 *
 * Implements the MerkleNodeInterface which defines basic Merkle Tree node
 * functionality.
 */
class MerkleNode implements MerkleNodeInterface
{
    public function __construct(
        private string $hash,
        private ?MerkleNode $left = null,
        private ?MerkleNode $right = null
    ) {
    }

    /**
     * Lazily recalculates the hash only when necessary.
     *
     * @return string The recalculated hash.
     */
    private function recalculateHash(): string
    {
        if ($this->left !== null && $this->right !== null) {
            return Hashing::hash($this->left->getHash() . $this->right->getHash(), 'sha512');
        }

        return $this->hash ?? '';
    }

    /**
     * Verifies the integrity of this node based on its children.
     *
     * @return bool True if the hash matches the calculated hash from children, false otherwise.
     */
    public function verifyIntegrity(): bool
    {
        $calculatedHash = $this->recalculateHash();
        return $this->hash === $calculatedHash;
    }

    /**
     * Efficiently returns the hash of this node.
     *
     * @return string The cached or recalculated hash.
     */
    public function getHash(): string
    {
        if ($this->hash === null) {
            $this->hash = $this->recalculateHash();
        }
        return $this->hash;
    }

    /**
     * Returns if the node is a leaf node (no children).
     *
     * @return bool True if it is a leaf node, false otherwise.
     */
    public function isLeaf(): bool
    {
        return $this->left === null && $this->right === null;
    }

    /**
     * Sets the left child node and invalidates the hash.
     *
     * @param MerkleNode|null $left Left child node.
     * @return void
     */
    public function setLeft(?MerkleNode $left): void
    {
        $this->left = $left;
    }

    /**
     * Sets the right child node and invalidates the hash.
     *
     * @param MerkleNode|null $right Right child node.
     * @return void
     */
    public function setRight(?MerkleNode $right): void
    {
        $this->right = $right;
    }

    /**
     * Gets the left child node.
     *
     * @return MerkleNode|null The left child node, or null if it doesn't exist.
     */
    public function getLeft(): ?MerkleNode
    {
        return $this->left;
    }

    /**
     * Gets the right child node.
     *
     * @return MerkleNode|null The right child node, or null if it doesn't exist.
     */
    public function getRight(): ?MerkleNode
    {
        return $this->right;
    }

    /**
     * Converts the node to an array, but avoids recalculations unless necessary.
     *
     * @return array Node as an array structure.
     */
    public function toArray(): array
    {
        return [
            'hash' => $this->getHash(),
            'left' => $this->left?->toArray(),
            'right' => $this->right?->toArray(),
        ];
    }

    /**
     * Returns a string representation of the node.
     *
     * @return string Node as a string.
     */
    public function __toString(): string
    {
        return sprintf(
            'MerkleNode(hash: %s, left: %s, right: %s)  [%s]',
            $this->getHash(),
            $this->left ? $this->left->getHash() : 'null',
            $this->right ? $this->right->getHash() : 'null',
            $this->verifyIntegrity() ? 'valid' : 'invalid'
        );
    }
}
