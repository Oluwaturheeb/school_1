class Validate {
	constructor() {
		this.success = false;
		this.pass = false;
		this.error = '';
	}

	validator(data = {}) {
		this.error = '';
		var field = Object.keys(data);

		for (let i in field) {
			var input = this.getInput(field[i]);
			var rules = Object.keys(Object.values(data)[i]);
			var ruleVal = Object.values(Object.values(data)[i]);
			var name = Object.values(Object.values(data))[i].field;
			name = this.capFirst(name);
			for (let o in rules) {
				if (this.error) {
					break;
				} else {
					if (rules[o] == 'require') {
						if (this.empty(field[i])) {
							this.error = name + ' field can not be empty!';
							break;
						}
					} else if (rules[o] == 'email') {
						if (input.indexOf('.') == -1 || input.indexOf('@') == -1) {
							this.error = 'Kindly provide a valid email address';
							break;
						}
					} else if (rules[o] == 'number') {
						if (isNaN(input)) {
							this.error = ' enter a numeric value for ' + name + '!';
							break;
						}
					} else if (rules[o] == 'wordcount') {
						var word = input.split(' ');
						if (ruleVal[o] > word.length) {
							this.error = 'At least ' + ruleVal[o] + ' word is required for ' + name;
							break;
						}
					} else if (rules[o] == 'min') {
						if (ruleVal[o] > input.length) {
							this.error = 'Minimum of ' + ruleVal[o] + ' chars is required for ' + name;
							break;
						}
					} else if (rules[o] == 'max') {
						if (input.length > ruleVal[o]) {
							this.error = 'Maximum of ' + ruleVal[o] + ' chars exceeded for ' + name + '!';
							break;
						}
					} else if (rules[o] == 'match') {
						if (!this.checkMatch(field[i], ruleVal[o])) {
							this.error = "Passwords do not match!!!";
							break;
						}
					} else if (rules[o] == 'file') {
						if (!this.fileCheck(field[i])) {
							this.error = 'Kindly, select a file!';
						}
					} else if (rules[o] == 'fileMin') {
						if (ruleVal[o] > this.fileCheck(field[i])) {
							this.error = 'Minimum of ' + ruleVal[o] + ' files is required!';
						}
					} else if (rules[o] == 'fileMax') {
						if (this.fileCheck(field[i]) > ruleVal[o]) {
							this.error = 'Maximum of ' + ruleVal[o] + ' files exceeded!';
						}
					} else if (rules[o] == 'checkBox') {
						if (this.checkBox() === false) {
							this.error = 'Select a value!';
						}
					}
				}
			}
		}
		if (this.error == '') {
			this.pass = true;
		}
	}

	capFirst(str) {
		return str.replace("/^./", str[0].toUpperCase());
	}

	getInput(input) {
		return $(input).val();
	}

	empty(handler, c = false) {
		var i = this.getInput(handler);

		if (c) {
			if (handler == undefined || handler == null || handler.length == 0) {
				return true;
			} else {
				return false;
			}
		} else {
			if (i == null || i == undefined) {
				return true;
			} else if (i.trim().length == 0) {
				return true;
			} else {
				return false;
			}
		}
	}
	
	 checkBox () {
		if (this.empty($('input:checkbox:checked').val(), true)) {
			return false;
		} else {
			return true;
		}
	}

	redirect(loc = '') {
		if (loc === '') {
			setTimeout(() => {
				location.reload();
			}, 1000);
		} else {
			setTimeout(() => {
				location = loc;
			}, 1000);
		}
	}

	store(value, key) {
		var store = localStorage;
		if (typeof value === 'string') {
			switch (key) {
				case 'rm':
					store.removeItem(value);
					break;
				case 'get':
					return store.getItem(value);
			}
		} else {
			switch (key) {
				case 'set':
					store.setItem(value[0], value[1]);
					break;
			}
		}
	}

	checkMatch(field, toMatch) {
		var field = this.getInput(field);
		var toMatch = this.getInput(toMatch);

		if (field === toMatch) {
			return true;
		}
		return false;
	}

	fileCheck(field) {
		var f = $(field).get(0).files.length;

		if (f) {
			return f;
		}
		return false;
	}

	thrower() {
		return this.error;
	}

	check() {
		return this.pass;
	}

	dError(val, t = 'b') {
		switch (t) {
			case 'a':
				alert(JSON.stringify(val));
				break;
			case 'b':
				try {
					val
				} catch (e) {
					alert(e);
				}
		}
	}

	uniqueData(sup = [], handler, err = '') {
		var table = sup[0];
		var col = sup[1];
		var check = this.getInput(sup[2]);
		var handler = $(handler);

		$.ajax({
			data: {
				1: table,
				2: col,
				3: check
			},
			success: e => {
				if (e == 'ok') {
					handler.html('')
				} else {
					handler.html(err)
				}
			}
		});
	}

	login(f, val, load) {
		this.validator(val);
		var info = $('.login-info');
		if (!this.check()) {
			info.html(this.thrower());
		} else {
			$.ajax({
				data: $(f).serialize(),
				beforeSend: () => {
					info.html(load + 'Please wait...');
				},
				success: e => {
					if (e == 'ok') {
						info.html('Logged!');
						this.redirect();
					} else {
						info.html("Credentials does not match any account!");
					}
				}
			});
		}
	}

	connect(f, info, load = '', msg = '', r = '', t) {
		stop();
		
		var ppt = {
			data: f,
			beforeSend: () => {
				info.html(load + 'Connecting to the server...');
			},
			success: e => {
				if (e == 'ok') {
					info.html('Success!');
					this.redirect(r);
				} else {
					if (msg) {
						info.html(msg);
					} else {
						info.html(e);
					}
				}
			}
		}
		$.ajax(ppt);
	}

	back(f, val, info, load = '', msg = '', red = '') {
		var info = $(info);
		
		if(f[0] == 'custom') {
			f = f[1];
		} else {
			f = $(f).serialize();
		}
		
		if (val) {
			this.validator(val);
			if (this.check() == false) {
				info.html(this.thrower());
			} else {
				this.connect(f, info, load, msg, red);
			}
		} else {
			this.connect(f, info, load, msg, red);
		}

	}
}
var v = new Validate();

/*class Validate {
    constructor() {
        this.success = false;
        this.pass = false;
        this.error = '';
    }

    validator(data = {}) {
        this.error = '';
        var field = Object.keys(data);

        for (let i in field) {
            var input = this.getInput(field[i]);
            var rules = Object.keys(Object.values(data)[i]);
            var ruleVal = Object.values(Object.values(data)[i]);
            var name = Object.values(Object.values(data))[i].field;
            name = this.capFirst(name);

            for (let o in rules) {
                if (this.error) {
                    break;
                } else {
                    if (rules[o] == 'require') {
                        if (this.empty(field[i])) {
                            this.error = name + ' field can not be empty!';
                            break;
                        }
                    } else if (rules[o] == 'email') {
                        if (input.indexOf('.') == -1 || input.indexOf('@') == -1) {
                            this.error = 'Kindly provide a valid email address';
                            break;
                        }
                    } else if (rules[o] == 'number') {
                        if (isNaN(input)) {
                            this.error = ' enter a numeric value for ' + name + '!';
                            break;
                        }
                    } else if (rules[o] == 'wordcount') {
                        var word = input.split(' ');
                        if (ruleVal[o] > word.length) {
                            this.error = 'At least ' + ruleVal[o] + ' word is required for ' + name;
                            break;
                        }
                    } else if (rules[o] == 'min') {
                        if (ruleVal[o] > input.length) {
                            this.error = 'Minimum of ' + ruleVal[o] + ' chars is required for ' + name;
                            break;
                        }
                    } else if (rules[o] == 'max') {
                        if (input.length > ruleVal[o]) {
                            this.error = 'Maximum of ' + ruleVal[o] + ' chars exceeded for ' + name + '!';
                            break;
                        }
                    } else if (rules[o] == 'match') {
                        if (!this.checkMatch(field[i], ruleVal[o])) {
                            this.error = "Passwords do not match!!!";
                            break;
                        }
                    } else if (rules[o] == 'file') {
                        if (!this.fileCheck(field[i])) {
                            this.error = 'Kindly, select a file!';
                        }
                    } else if (rules[o] == 'fileMin') {
                        if (ruleVal[o] > this.fileCheck(field[i])) {
                            this.error = 'Minimum of ' + ruleVal[o] + ' files is required!';
                        }
                    } else if (rules[o] == 'fileMax') {
                        if (this.fileCheck(field[i]) > ruleVal[o]) {
                            this.error = 'Maximum of ' + ruleVal[o] + ' files exceeded!';
                        }
                    }
                }
            }
        }
        if (this.error == '') {
            this.pass = true;
        }
    }

    capFirst(str) {
        return str.replace("/^./", str[0].toUpperCase());
    }

    getInput(input) {
        return $(input).val();
    }

    empty(handler, c = '') {
        var i = this.getInput(handler);

        if (c != '') {
            if (handler == undefined || handler == null || handler.length == 0) {
                return true;
            } else {
                return false;
            }
        } else {
            if (i == null || i == undefined) {
                return true;
            } else if (i.trim().length == 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    redirect(loc = '') {
        if (loc === '') {
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            setTimeout(() => {
                location = loc + '.php';
            }, 1000);
        }
    }

    store(value, key) {
        var store = localStorage;
        if (typeof value === 'string') {
            switch (key) {
                case 'rm':
                    store.removeItem(value);
                    break;
                case 'get':
                    return store.getItem(value);
            }
        } else {
            switch (key) {
                case 'set':
                    store.setItem(value[0], value[1]);
                    break;
            }
        }
    }

    checkMatch(field, toMatch) {
        var field = this.getInput(field);
        var toMatch = this.getInput(toMatch);

        if (field === toMatch) {
            return true;
        }
        return false;
    }

    fileCheck(field) {
        var f = $(field).get(0).files.length;

        if (f) {
            return f;
        }
        return false;
    }

    thrower() {
        return this.error;
    }

    check() {
        return this.pass;
    }

    uniqueData(sup = [], handler, err = '') {
        var table = sup[0];
        var col = sup[1];
        var check = this.getInput(sup[2]);
        var handler = $(handler);

        $.ajax({
            data: {
                1: table,
                2: col,
                3: check
            },
            success: e => {
                if (e == 'ok') {
                    handler.html('')
                } else {
                    handler.html(err)
                }
            }
        })
    }
}
var v = new Validate();*/

