<?php
declare(strict_types=1);
namespace App\Blockchain\Core\MerkleTree;

use App\Blockchain\Exceptions\InvalidHashException;
use App\Blockchain\Interfaces\MerkleTree\MerkleTreeInterface;
use App\Blockchain\Cryptography\Hashing;

/**
 * Class MerkleTree
 *
 * This class implements the MerkleTreeInterface and manages the construction
 * of a Merkle Tree using transaction hashes. It provides methods to add
 * transactions, build the tree, retrieve the root hash, and clear the tree.
 *
 * The Merkle Tree structure allows efficient verification of data integrity
 * through its hash hierarchy, and this class ensures that the tree can be
 * dynamically updated with new transactions.
 *
 * @package App\Blockchain\Core\MerkleTree
 */
class MerkleTree implements MerkleTreeInterface
{
    protected array $transactions = [];
    private ?MerkleNode $rootNode = null;

    /**
     * Adds a batch of transactions to the Merkle tree.
     *
     * @param array $transactionHashes Array of transaction hashes.
     */
    public function addTransactions(array $transactionHashes): void
    {
        foreach ($transactionHashes as $hash) {
            if (!is_string($hash) || empty($hash)) {
                throw new InvalidHashException($hash);
            }
        }
        $this->transactions = array_merge($this->transactions, $transactionHashes);
        $this->rootNode = null;
    }

    /**
     * Builds the Merkle tree and returns the root hash.
     *
     * @return string The root hash of the Merkle tree.
     */
    public function getRootHash(): string
    {
        if ($this->rootNode === null) {
            $this->rootNode = $this->buildTree($this->transactions);
        }

        return $this->rootNode->getHash();
    }

    /**
     * Returns the transactions in the Merkle tree.
     * @return array Array of transactions.
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }

    /**
     * Builds the Merkle tree from the bottom up in an efficient manner.
     *
     * @param array $hashes List of transaction hashes.
     * @return MerkleNode The root node of the Merkle tree.
     */
    private function buildTree(array $hashes): MerkleNode
    {
        $nodeCount = count($hashes);
        if ($nodeCount === 0) {
            return new MerkleNode('');
        }

        if ($nodeCount === 1) {
            return new MerkleNode($hashes[0]);
        }

        if ($nodeCount % 2 !== 0) {
            $hashes[] = end($hashes);
        }

        $currentLevel = array_map(fn($hash) => new MerkleNode($hash), $hashes);

        while (count($currentLevel) > 1) {
            $nextLevel = [];

            for ($i = 0; $i < count($currentLevel); $i += 2) {
                $leftNode = $currentLevel[$i];
                $rightNode = ($i + 1 < count($currentLevel)) ? $currentLevel[$i + 1] : $leftNode;

                $combinedHash = $this->hashNodes($leftNode, $rightNode);
                $nextLevel[] = new MerkleNode($combinedHash, $leftNode, $rightNode);
            }

            $currentLevel = $nextLevel;
        }

        return $currentLevel[0];
    }

    private function hashNodes(MerkleNode $leftNode, MerkleNode $rightNode): string
    {
        return Hashing::hash($leftNode->getHash() . $rightNode->getHash(), 'sha256');
    }

    /**
     * Clears the tree and invalidates the root.
     */
    public function clear(): void
    {
        $this->transactions = [];
        $this->rootNode = null;
    }

    /**
     * Efficiently prints the structure of the Merkle tree.
     */
    public function printTree(): void
    {
        if ($this->rootNode !== null) {
            $this->printNode($this->rootNode, 0);
        }
    }

    /**
     * Recursive helper function to print the tree structure.
     *
     * @param MerkleNode $node The current node.
     * @param int $depth The depth of the current node.
     */
    private function printNode(MerkleNode $node, int $depth): void
    {
        echo str_repeat("  ", $depth) . $node->getHash() . PHP_EOL;

        if ($node->getLeft() !== null) {
            $this->printNode($node->getLeft(), $depth + 1);
        }

        if ($node->getRight() !== null) {
            $this->printNode($node->getRight(), $depth + 1);
        }
    }
}
