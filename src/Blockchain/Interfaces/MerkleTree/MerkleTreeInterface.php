<?php
declare(strict_types=1);
namespace App\Blockchain\Interfaces\MerkleTree;

/**
 * Interface MerkleTreeInterface
 *
 * This interface defines the essential methods required to manage
 * a Merkle Tree. The tree can store a collection of transaction hashes
 * and provides functionality to build the tree structure, calculate
 * the root hash, and manage the tree's state.
 *
 * @package App\Blockchain\Interfaces\MerkleTree
 */
interface MerkleTreeInterface
{
    /**
     * Adds a batch of transactions to the Merkle tree.
     *
     * @param array $transactionHashes Array of transaction hashes.
     */
    public function addTransactions(array $transactionHashes): void;

    /**
     * Builds the Merkle tree and returns the root hash.
     *
     * @return string The root hash of the Merkle tree.
     */
    public function getRootHash(): string;

    /**
     * Clears the tree and invalidates the root.
     */
    public function clear(): void;

    /**
     * Efficiently prints the structure of the Merkle tree.
     */
    public function printTree(): void;
}

