describe("Kata02", function() {
  
  describe("chop function", function() {

    it("noArray to be rejected", function() {
      expect(chop(3, 'bullshit')).toBe(-1);
    });

    it("3 in empty to be -1", function() {
      expect(chop(3, [])).toBe(-1);
    });

    it("-1, chop(3, [1]", function() {
      expect(chop(3, [1])).toBe(-1);
    });

    it("0, chop(1, [1])", function() {
      expect(chop(1, [1])).toBe(0);
    });

    it("0, chop(1, [1, 3, 5])", function() {
      expect(chop(1, [1, 3, 5])).toBe(0);
    });

    it("1,  chop(3, [1, 3, 5])", function() {
      expect(chop(3, [1, 3, 5])).toBe(1);
    });

    it("2,  chop(5, [1, 3, 5])", function() {
      expect(chop(5, [1, 3, 5])).toBe(2);
    });

    it("-1, chop(0, [1, 3, 5])", function() {
      expect(chop(0, [1, 3, 5])).toBe(-1);
    });

    it("-1, chop(0, [1, 3, 5])", function() {
      expect(chop(0, [1, 3, 5])).toBe(-1);
    });

    it("-1, chop(0, [1, 3, 5])", function() {
      expect(chop(0, [1, 3, 5])).toBe(-1);
    });

    it("-1, chop(2, [1, 3, 5])", function() {
      expect(chop(2, [1, 3, 5])).toBe(-1);
    });

    it("-1, chop(4, [1, 3, 5])", function() {
      expect(chop(4, [1, 3, 5])).toBe(-1);
    });

    it("-1, chop(6, [1, 3, 5])", function() {
      expect(-1).toBe(chop(6, [1, 3, 5]));
    });

    it("0,  chop(1, [1, 3, 5, 7])", function() {
      expect(chop(1, [1, 3, 5, 7])).toBe(0);
    });

    it("1,  chop(3, [1, 3, 5, 7])", function() {
      expect(chop(3, [1, 3, 5, 7])).toBe(1);
    });

    it("2,  chop(5, [1, 3, 5, 7])", function() {
      expect(chop(5, [1, 3, 5, 7])).toBe(2);
    });

    it("3,  chop(7, [1, 3, 5, 7])", function() {
      expect(chop(7, [1, 3, 5, 7])).toBe(3);
    });

    it("-1, chop(0, [1, 3, 5, 7])", function() {
      expect(chop(0, [1, 3, 5, 7])).toBe(-1);
    });

    it("-1, chop(2, [1, 3, 5, 7])", function() {
      expect(chop(2, [1, 3, 5, 7])).toBe(-1);
    });

    it("-1, chop(4, [1, 3, 5, 7])", function() {
      expect(chop(4, [1, 3, 5, 7])).toBe(-1);
    });

    it("-1, chop(6, [1, 3, 5, 7])", function() {
      expect(chop(6, [1, 3, 5, 7])).toBe(-1);
    });

    it("-1, chop(8, [1, 3, 5, 7])", function() {
      expect(chop(8, [1, 3, 5, 7])).toBe(-1);
    });


  });

  
});
