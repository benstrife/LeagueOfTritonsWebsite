//--------------------------
// Filename: RST.hpp
// Author: Michael Chin
// Date: Oct 20, 2013
// Description:
//
//--------------------------

#ifndef RST_HPP
#define RST_HPP
#include "BST.hpp"

template <typename Data>
class RST : public BST<Data> {

public:

  virtual bool insert(const Data& item) {
    BSTNode<Data> *p = BST<Data>::root;
    BSTNode<Data> *newNode;

    //If no nodes, create the root
    if(BST<Data>::root == 0) {
      BST<Data>::root = new BSTNode<Data>(item);
      //std::cout << "\nRoot is " << BST<Data>::root->priority << "\n with data: " << BST<Data>::root->data << "\n with address \n" << BST<Data>::root;
    }
    //Else, find out where to insert it
    else {
      //Traverse through the tree
      while(1) {
        //Decide whether to traverse left or right
        if(item < p->data) {
          if(p->left == 0) {
            p->left = new BSTNode<Data>(item);
            p->left->parent = p;
            newNode = p->left;
            break;
          }
          else {
            p = p->left;
          }
        }
        else if(p->data < item) {
          if(p->right == 0) {
            p->right = new BSTNode<Data>(item);
            p->right->parent = p;
            newNode = p->right;
            break;
          }
          else {
            p = p->right;
          }
        }
        //Return false, there was a duplicate
        else {
	  return 0;
        }
      }

      //Rotate as necessary   
      while(newNode->parent != 0 && newNode->parent->priority < newNode->priority) {
        //Create pointer for parent  
        BSTNode<Data> *parent = newNode->parent;
        BSTNode<Data> *gparent = newNode->parent->parent;

        //std::cout << "Made it here \n ";

        //Do we rotate left or right
        if(parent->right == newNode) {
          parent = rotateLeft(parent); //
          parent->parent = newNode;
          newNode->left = parent;
         // std::cout << "rotateLeft";
        }
        else {
          parent = rotateRight(parent);//
          parent->parent = newNode;
          newNode->right = parent;
          //std::cout << "\nrotateRight";
        } 
        //If no grandparent, new root is made
        if(gparent == 0) {
          newNode->parent = 0;
         // std::cout << "\nchanging root\n";
          BST<Data>::root = newNode;
          break;
        }
        //Else, normal rotation
        else {
          newNode->parent = gparent;

          //The parent is a right child
          if(gparent->right == parent) {
            newNode->parent->right = newNode;
          }
          //The parent is a left child
          else {
            gparent->left = newNode;
          } 
        }

        //Reset parent
        parent = newNode->parent;
        //std::cout << newNode->priority << "\n";
      }
/*
    std::cout << newNode->right->priority << " " << newNode->priority;
    std::cout << "\n new root " << BST<Data>::root->priority;
    std::cout << "\n Left child is " << BST<Data>::root->left;
    std::cout << "\n Right child is " << BST<Data>::root->right->priority;
    std::cout << "\n Parent is " << BST<Data>::root->parent;

   std::cout << "\n right child... " << BST<Data>::root->right->priority;
    std::cout << "\n Left child is " << BST<Data>::root->right->left;
    std::cout << "\n Right child is " << BST<Data>::root->right->right;
    std::cout << "\n Parent is " << BST<Data>::root->right->parent->data;
*/
    }

    //Increment the size
    BST<Data>::isize++;
    
    //Return true, data inserted
    return 1;
  }

private:
  BSTNode<Data>* rotateRight(BSTNode<Data> *parent) {
    if(parent->left->right == 0) {
      parent->left = 0;
    }
    else { 
      parent->left = parent->left->right;
      parent->left->parent = parent;
    }
    return parent;
  }

  BSTNode<Data>* rotateLeft(BSTNode<Data> *parent) {
    if(parent->right->left == 0) {
      parent->right = 0;
    }
    else { 
      parent->right = parent->right->left;
      parent->right->parent = parent;
    }
    return parent;
  } 
};
#endif // RST_HPP