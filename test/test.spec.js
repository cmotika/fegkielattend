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


context('Waiting', () => {
  beforeEach(() => {
    cy.visit('http://www.delphino.net/feg' )
  })
  // BE CAREFUL of adding unnecessary wait times.
  // https://on.cypress.io/best-practices#Unnecessary-Waiting

  // https://on.cypress.io/wait
  it('cy.wait() - wait for a specific amount of time', () => {
    cy.get('#name').type('Name1')
    cy.get('[name="form1"] > .btn').click()
    //cy.wait(1000)
    cy.contains('Gib Deinen Vor- UND Nachnamen an.')
  })

})