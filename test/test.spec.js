/// <reference types="cypress" />

context('Input field tests', () => {
  beforeEach(() => {
    cy.visit('http://www.delphino.net/feg' )
  })

  it('Name field', () => {
    // Name too short (No surname)
    // #REQ035
    cy.get('#name').clear()
    cy.get('#name').type('Name1')
    cy.get('[name="form1"] > .btn').click()
    cy.contains('Gib Deinen Vor- UND Nachnamen an.')

    // Name too short 2
    // #REQ035
    cy.get('#name').clear()
    cy.get('#name').type('Na e')
    cy.get('[name="form1"] > .btn').click()
    cy.contains('Gib Deinen Vor- UND Nachnamen an.')

    // Name too short 3 (No second surname)
    // #REQ035
    cy.get('#name').clear()
    cy.get('#name').type('Forename Surname, Name2')
    cy.get('[name="form1"] > .btn').click()
    cy.contains('Gib Deinen Vor- UND Nachnamen an.')

    // Name too short 4
    // #REQ035
    cy.get('#name').clear()
    cy.get('#name').type('Forename Surname, Na e')
    cy.get('[name="form1"] > .btn').click()
    cy.contains('Gib Deinen Vor- UND Nachnamen an.')

    // Name contains plus
    // #REQ047
    cy.get('#name').clear()
    cy.get('#name').type('Forename Surname+Forename Surname')
    cy.get('[name="form1"] > .btn').click()
    cy.contains('Gib Deinen Vor- UND Nachnamen an.')

    // Name contains UND
    // #REQ048
    cy.get('#name').clear()
    cy.get('#name').type('Forename Surname und Forename Surname')
    cy.get('[name="form1"] > .btn').click()
    cy.contains('Gib Deinen Vor- UND Nachnamen an.')

    // Name correct 1
    cy.get('#name').clear()
    cy.get('#name').type('Forename Surname')
    cy.get('[name="form1"] > .btn').click()
    cy.contains('Gib Deinen Vor- UND Nachnamen an.').should('not.exist')

    // Name correct 2
    cy.get('#name').clear()
    cy.get('#name').type('Forename Middlename Surname')
    cy.get('[name="form1"] > .btn').click()
    cy.contains('Gib Deinen Vor- UND Nachnamen an.').should('not.exist')
  })

  it('Street field', () => {
    // Street too short
    // #REQ050
    cy.get('#street').clear()
    cy.get('#street').type('Stre')
    cy.get('[name="form1"] > .btn').click()
    cy.contains('e und Hausnummer an.')

    // Street no house number
    // #REQ050
    cy.get('#street').clear()
    cy.get('#street').type('Street')
    cy.get('[name="form1"] > .btn').click()
    cy.contains('e und Hausnummer an.')

    // Incorrect house number
    // #REQ050
    cy.get('#street').clear()
    cy.get('#street').type('Street a')
    cy.get('[name="form1"] > .btn').click()
    cy.contains('e und Hausnummer an.')

    // Street correct 1
    // #REQ019
    cy.get('#street').clear()
    cy.get('#street').type('Street 1')
    cy.get('[name="form1"] > .btn').click()
    cy.contains('e und Hausnummer an.').should('not.exist')

    // Street correct 2
    // #REQ019
    cy.get('#street').clear()
    cy.get('#street').type('Street 1a')
    cy.get('[name="form1"] > .btn').click()
    cy.contains('e und Hausnummer an.').should('not.exist')
  })


  it('City field', () => {
    // City too short
    // #REQ051
    cy.get('#city').clear()
    cy.get('#city').type('City')
    cy.get('[name="form1"] > .btn').click()
    cy.contains('Gib Deine Postleitzahl und Stadt an.')

    // No ZIP
    // #REQ051
    cy.get('#city').clear()
    cy.get('#city').type('aaaaaaaa')
    cy.get('[name="form1"] > .btn').click()
    cy.contains('Gib Deine Postleitzahl und Stadt an.')

    // City too short
    // #REQ051
    cy.get('#city').clear()
    cy.get('#city').type('12345 ab')
    cy.get('[name="form1"] > .btn').click()
    cy.contains('Gib Deine Postleitzahl und Stadt an.')

    // Invalid ZIP 1
    // #REQ051
    cy.get('#city').clear()
    cy.get('#city').type('1234 aab')
    cy.get('[name="form1"] > .btn').click()
    cy.contains('Gib Deine Postleitzahl und Stadt an.')

    // Invalid ZIP 2
    // #REQ051
    cy.get('#city').clear()
    cy.get('#city').type('1234a aab')
    cy.get('[name="form1"] > .btn').click()
    cy.contains('Gib Deine Postleitzahl und Stadt an.')

    // City correct
    // #REQ020
    cy.get('#city').clear()
    cy.get('#city').type('12345 abc')
    cy.get('[name="form1"] > .btn').click()
    cy.contains('Gib Deine Postleitzahl und Stadt an.').should('not.exist')
  })

  it('Phone field', () => {
    // Phone too short
    // #REQ049
    cy.get('#phone').clear()
    cy.get('#phone').type('1234')
    cy.get('[name="form1"] > .btn').click()
    cy.contains('Gib Deine Telefonnummer an.')

    // Phone correct
    // #REQ021
    cy.get('#phone').clear()
    cy.get('#phone').type('12345')
    cy.get('[name="form1"] > .btn').click()
    cy.contains('Gib Deine Telefonnummer an.').should('not.exist')
  })

  it('Email field', () => {
    // Email too short
    // #REQ022
    cy.get('#email').clear()
    cy.get('#email').type('1234')
    cy.get('[name="form1"] > .btn').click()
    cy.contains('Gib Deine E-Mail-Adresse an.')

    // Email no @
    // #REQ022
    cy.get('#email').clear()
    cy.get('#email').type('12345')
    cy.get('[name="form1"] > .btn').click()
    cy.contains('Gib Deine E-Mail-Adresse an.')

    // Email no host
    // #REQ022
    cy.get('#email').clear()
    cy.get('#email').type('abc@')
    cy.get('[name="form1"] > .btn').click()
    cy.contains('Gib Deine E-Mail-Adresse an.')

    // Email invalid host 1
    // #REQ022
    cy.get('#email').clear()
    cy.get('#email').type('abc@abc')
    cy.get('[name="form1"] > .btn').click()
    cy.contains('Gib Deine E-Mail-Adresse an.')

    // Email invalid host 2
    // #REQ022
    cy.get('#email').clear()
    cy.get('#email').type('abc@.com')
    cy.get('[name="form1"] > .btn').click()
    cy.contains('Gib Deine E-Mail-Adresse an.')

    // Email no name
    // #REQ022
    cy.get('#email').clear()
    cy.get('#email').type('@abc.com')
    cy.get('[name="form1"] > .btn').click()
    cy.contains('Gib Deine E-Mail-Adresse an.')

    // Email correct
    // #REQ022
    cy.get('#email').clear()
    cy.get('#email').type('a@a.de')
    cy.get('[name="form1"] > .btn').click()
    cy.contains('Gib Deine E-Mail-Adresse an.').should('not.exist')
  })


})




context('New list entry submission', () => {
  beforeEach(() => {
    // Set one default entry with number #5
    cy.visit('http://www.delphino.net/feg' )
    cy.get('.btn-outline-secondary').click()

    cy.get('#pw').clear()
    cy.get('#pw').type('admin')
    cy.get('label > .btn').click()

    // set maximum of 3 people
    cy.get('#nmaxnum').clear()
    cy.get('#nmaxnum').type('3')
    cy.get('label > input').click()

    cy.get('textarea').clear()
    cy.get('textarea').type('5;ab cd;ab 1;12345 abc;12345;a@b.de{enter}')
    cy.get('[name="savefile"]').click()
  })

  // Wrong code
   it('Submit code wrong', () => {
    cy.get('#name').clear()
    cy.get('#name').type('Na me1')
    cy.get('#street').clear()
    cy.get('#street').type('Street 1')
    cy.get('#city').clear()
    cy.get('#city').type('12345 Abc')
    cy.get('#phone').clear()
    cy.get('#phone').type('012345')
    cy.get('#email').clear()
    cy.get('#email').type('a@b.de')

    cy.get('[name="form1"] > .btn').click()
    cy.contains('Bitte Rechenaufgabe korrekt')
  })

  // Correct entry
  it('Correct entry', () => {
    cy.visit('http://delphino.net/feg/?test=97y2o3lrnewdsa0AS8UAPOIHKNF3R9PHAOSD@!$$' )
    cy.get('#name').clear()
    cy.get('#name').type('Na me1')
    cy.get('#street').clear()
    cy.get('#street').type('Street 1')
    cy.get('#city').clear()
    cy.get('#city').type('12345 Abc')
    cy.get('#phone').clear()
    cy.get('#phone').type('012345')
    cy.get('#email').clear()
    cy.get('#email').type('a@b.de')

    cy.get('[name="form1"] > .btn').click()
    cy.contains('Bitte Rechenaufgabe korrekt').should('not.exist')
    cy.contains('Du bist nun erfolgreich f')
    cy.contains('#6')
  })


  // Correct multi-entry
  it('Correct multi-entry', () => {
    cy.visit('http://delphino.net/feg/?test=97y2o3lrnewdsa0AS8UAPOIHKNF3R9PHAOSD@!$$' )
    cy.get('#name').clear()
    cy.get('#name').type('Na me1, Na me2')
    cy.get('#street').clear()
    cy.get('#street').type('Street 1')
    cy.get('#city').clear()
    cy.get('#city').type('12345 Abc')
    cy.get('#phone').clear()
    cy.get('#phone').type('012345')
    cy.get('#email').clear()
    cy.get('#email').type('a@b.de')

    cy.get('[name="form1"] > .btn').click()
    cy.contains('Bitte Rechenaufgabe korrekt').should('not.exist')
    cy.contains('Ihr seid nun erfolgreich f')
    cy.contains('#6')
    cy.contains('#7')
  })

  // No double entry
  it('Double entry', () => {
    cy.visit('http://delphino.net/feg/?test=97y2o3lrnewdsa0AS8UAPOIHKNF3R9PHAOSD@!$$' )
    cy.get('#name').clear()
    cy.get('#name').type('Na me1')
    cy.get('#street').clear()
    cy.get('#street').type('Street 1')
    cy.get('#city').clear()
    cy.get('#city').type('12345 Abc')
    cy.get('#phone').clear()
    cy.get('#phone').type('012345')
    cy.get('#email').clear()
    cy.get('#email').type('a@b.de')
    cy.get('[name="form1"] > .btn').click()

    cy.get('#name').clear()
    cy.get('#name').type('Na me1')
    cy.get('#street').clear()
    cy.get('#street').type('Street 1')
    cy.get('#city').clear()
    cy.get('#city').type('12345 Abc')
    cy.get('#phone').clear()
    cy.get('#phone').type('012345')
    cy.get('#email').clear()
    cy.get('#email').type('a@b.de')
    cy.get('[name="form1"] > .btn').click()

    cy.contains('schon angemeldet!')
  })


    // Not enough seats
    it('Not enough seats', () => {
      cy.visit('http://delphino.net/feg/?test=97y2o3lrnewdsa0AS8UAPOIHKNF3R9PHAOSD@!$$' )
      cy.get('#name').clear()
      cy.get('#name').type('Na me1, Name 2, Name 3')
      cy.get('#street').clear()
      cy.get('#street').type('Street 1')
      cy.get('#city').clear()
      cy.get('#city').type('12345 Abc')
      cy.get('#phone').clear()
      cy.get('#phone').type('012345')
      cy.get('#email').clear()
      cy.get('#email').type('a@b.de')
      cy.get('[name="form1"] > .btn').click()

      cy.contains('Es sind leider nicht gen')
    })

    // No seats left after submission
    it('No seats left after submission', () => {
      cy.visit('http://delphino.net/feg/?test=97y2o3lrnewdsa0AS8UAPOIHKNF3R9PHAOSD@!$$' )
      cy.get('#name').clear()
      cy.get('#name').type('Na me1, Na me2')
      cy.get('#street').clear()
      cy.get('#street').type('Street 1')
      cy.get('#city').clear()
      cy.get('#city').type('12345 Abc')
      cy.get('#phone').clear()
      cy.get('#phone').type('012345')
      cy.get('#email').clear()
      cy.get('#email').type('a@b.de')
      cy.get('[name="form1"] > .btn').click()

      cy.visit('http://delphino.net/feg/?test=97y2o3lrnewdsa0AS8UAPOIHKNF3R9PHAOSD@!$$' )
      cy.contains('Keine freien Pl')
    })

    // No seats left for next submission
    it('No seats left for next submission', () => {
      cy.visit('http://delphino.net/feg/?test=97y2o3lrnewdsa0AS8UAPOIHKNF3R9PHAOSD@!$$' )
      cy.get('#name').clear()
      cy.get('#name').type('Na me1, Na me2')
      cy.get('#street').clear()
      cy.get('#street').type('Street 1')
      cy.get('#city').clear()
      cy.get('#city').type('12345 Abc')
      cy.get('#phone').clear()
      cy.get('#phone').type('012345')
      cy.get('#email').clear()
      cy.get('#email').type('a@b.de')
      cy.get('[name="form1"] > .btn').click()

      cy.visit('http://delphino.net/feg/?test=97y2o3lrnewdsa0AS8UAPOIHKNF3R9PHAOSD@!$$' )
      cy.get('#name').clear()
      cy.get('#name').type('Na me1, Na me2')
      cy.get('#street').clear()
      cy.get('#street').type('Street 1')
      cy.get('#city').clear()
      cy.get('#city').type('12345 Abc')
      cy.get('#phone').clear()
      cy.get('#phone').type('012345')
      cy.get('#email').clear()
      cy.get('#email').type('a@b.de')
      cy.get('[name="form1"] > .btn').click()

      cy.contains('Es sind leider schon alle Pl')
    })


})