import math;
class GProgression:
 
 # -- поля -- #
 firstEl=0
 denominator=0
 
 # конструктор без параметрів, ввід за замовчуванням
 def __init__(self):
   self.enterFirstEl() 
   self.enterDenominator()

# конструктор з параметрами
 def __init__(self,_firstEl,_denominator):
   self.firstEl = _firstEl
   self.denominator = _denominator

 # методи вводу
 def enterFirstEl(self):
  self.firstEl = int(input())

 def enterDenominator(self):
  self.denominator = int(input())

 # методи виводу
 def printFirstEl(self):
  print(self.firstEl)

 def printDenominator(self):
  print(self.denominator)

 # методи прогрессії
 def getNElement(self,n):
    return self.firstEl * (self.denominator ** (n - 1))
 
 def getNElementSum(self,n):
    element_sum = 0
    current_element = self.firstEl
    for i in range(n):
            element_sum += current_element
            current_element *= self.denominator
    return element_sum
 
progression = GProgression(2,5)
progression.printFirstEl()
progression.printDenominator()
print(progression.getNElement(3))
print(progression.getNElementSum(5))