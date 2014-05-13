describe("Expense objects", function(){

    var theExpenseItem, theExpense;

    beforeEach(function(){
        theExpenseItem = new expenseItem(100);
        theExpense = new expense(theExpenseItem);
    });

   it("should be of type ExpenseItem", function(){
       expect(theExpense.expenseItem).toBe(theExpenseItem);
   });

    it("should have the correct expense amount", function(){
        expect(theExpense.expenseItem.amount).toEqual(100);
    });
});