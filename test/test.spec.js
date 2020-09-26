/// Regressions tests for append.php

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
    cy.visit('http://www.delphino.net/feg' )
    cy.get('.btn-outline-secondary').click()

	// Login as admin
    cy.get('#pw').clear()
    cy.get('#pw').type('admin')
    cy.get('label > .btn').click()

    // Set maximum of 3 people
    cy.get('#nmaxnum').clear()
    cy.get('#nmaxnum').type('3')
    cy.get('label > input').click()

    // Set one default entry with number #5
    cy.get('textarea').clear()
    cy.get('textarea').type('5;ab cd;ab 1;12345 abc;12345;a@b.de{enter}')
    cy.get('[name="savefile"]').click()
  })

  // Wrong code
  // #REQ012
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
  // #REQ026
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
  // #REQ027
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
  // #REQ017
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
  // #REQ053
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
 // #REQ053
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
  // #REQ052
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


context('Admin functions', () => {
  beforeEach(() => {
    // Set one default entry with number #5
    cy.visit('http://www.delphino.net/feg')
    cy.get('.btn-outline-secondary').click()
    cy.get('#pw').clear()
    cy.get('#pw').type('admin')
    cy.get('label > .btn').click()
    cy.get('textarea').clear()
    cy.get('textarea').type('5;ab cd;ab 1;12345 abc;12345;a@b.de{enter}')
    cy.get('[name="savefile"]').click()
  })

  // Submit and find entry in text field
  it('Submit entry and find in text field', () => {
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

      cy.visit('http://www.delphino.net/feg')
      cy.get('.btn-outline-secondary').click()
      cy.get('#pw').clear()
      cy.get('#pw').type('admin')
      cy.get('label > .btn').click()

      cy.get('textarea').contains('Na me1; Street 1; 12345 Abc; 012345; a@b.de')
//      cy.contains('Es sind leider schon alle Pl')
  })

  // Change the password (not working, because not equal)
  it('Change password - wrong', () => {
      cy.get(':nth-child(2) > :nth-child(3) > #pw').clear()
      cy.get(':nth-child(2) > :nth-child(3) > #pw').type('admin')

      cy.get(':nth-child(3) > :nth-child(3) > #pw').clear()
      cy.get(':nth-child(3) > :nth-child(3) > #pw').type('admin2')

      cy.get('label > input').click()

      // Check if NOT changed (due to errr)

      cy.visit('http://www.delphino.net/feg')
      cy.get('.btn-outline-secondary').click()
      cy.get('#pw').clear()
      cy.get('#pw').type('admin')
      cy.get('label > .btn').click()

      cy.get('textarea').contains('5;ab cd')
//      cy.contains('Es sind leider schon alle Pl')
  })

  // Change the password (working, because equal) and change it back
  it('Change password - correct', () => {
      cy.get(':nth-child(2) > :nth-child(3) > #pw').clear()
      cy.get(':nth-child(2) > :nth-child(3) > #pw').type('admin2')
      cy.get(':nth-child(3) > :nth-child(3) > #pw').clear()
      cy.get(':nth-child(3) > :nth-child(3) > #pw').type('admin2')
      cy.get('label > input').click()

      // Check if changed
      cy.visit('http://www.delphino.net/feg')
      cy.get('.btn-outline-secondary').click()
      cy.get('#pw').clear()
      cy.get('#pw').type('admin2')
      cy.get('label > .btn').click()

      cy.get('textarea').contains('5;ab cd')

      // Now change back
      cy.get(':nth-child(2) > :nth-child(3) > #pw').clear()
      cy.get(':nth-child(2) > :nth-child(3) > #pw').type('admin')
      cy.get(':nth-child(3) > :nth-child(3) > #pw').clear()
      cy.get(':nth-child(3) > :nth-child(3) > #pw').type('admin')
      cy.get('label > input').click()
  })

  // Change the settings (and change back)
  it('Change settings', () => {
      cy.get('#nmaxnum').clear()
      cy.get('#nmaxnum').type('444')
      cy.get('#nswitchtime').clear()
      cy.get('#nswitchtime').type('2')
      cy.get('label > input').click()

      // Check if changed
      cy.visit('http://www.delphino.net/feg')
      cy.get('.btn-outline-secondary').click()
      cy.get('#pw').clear()
      cy.get('#pw').type('admin')
      cy.get('label > .btn').click()

      cy.get('#nmaxnum').should('have.value', '444')
      cy.get('#nswitchtime').should('have.value', '2')

      // Change back
      cy.get('#nmaxnum').clear()
      cy.get('#nmaxnum').type('4')
      cy.get('#nswitchtime').clear()
      cy.get('#nswitchtime').type('10')
      cy.get('label > input').click()
  })

  // Print list
  it('Print list', () => {
    cy.visit('http://delphino.net/feg/?test=97y2o3lrnewdsa0AS8UAPOIHKNF3R9PHAOSD@!$$' )
    cy.get('.btn-outline-secondary').click()
    cy.get('#pw').clear()
    cy.get('#pw').type('admin')
    cy.get('label > .btn').click()
    cy.get('[name="print"]').click()
    cy.contains('Anmeldeliste f')
    cy.contains('r den GoDi am')
  })

  // Wrong admin password
  // #REQ055
  it('Wrong admin password', () => {
    cy.visit('http://www.delphino.net/feg')

    cy.get('.btn-outline-secondary').click()
    cy.get('#pw').clear()
    cy.get('#pw').type('wrongpassword')
    cy.get('label > .btn').click()

    cy.get('.btn-outline-secondary').click()
    cy.contains('unimplemented')
  })


})