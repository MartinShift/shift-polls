let words = 'day\nwhere\ndevelop\ngreat\nor\nwith\nlike\nvery\nlarge\nboth\nday\nfew\nthan\nkeep\nthat\nuse\nown\nprogram\npoint\nhave\nanother\nhand\ntime\nplace\nthat\nwhat\nrun\nhome\nby\nlike\nhouse\ngeneral\nfollow\npart\nwhere\ntell\nget\npeople\nthink\nnumber\nfollow\nkeep\nmost\nask\nstand\nlike\nmust\nmore\nprogram\ngood\nset\nshow\nhead\nwill\nfind\nbecome\nbecause\npublic\nwant\nwrite';
// document.getElementById('words').innerText;
Array.from(words.replaceAll('\n',' ')).forEach(l => document.dispatchEvent(new KeyboardEvent('keydown', {keycode: `${l}`})))

Array.from(document.getElementById("words").innerText.replaceAll('\n',' ')).forEach(x => document.dispatchEvent(new KeyboardEvent('keydown', {key: `${x}`})))