#include <iostream>

class HelloWorld
{
public:
   int helloWorld()
   {
      std::cout << "Hello World!";
   }
};

int main()
{
   (new HelloWorld)->helloWorld();
}