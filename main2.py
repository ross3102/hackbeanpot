import eng_to_ipa
from pydub import AudioSegment, effects
from pydub.playback import play


def word_to_sound(word):
    ipa = eng_to_ipa.convert(word)  # , retrieve_all=True)[0]
    return ipa


def phrase_to_sound(phrase):
    return [word_to_sound(word) for word in phrase.split()]


def detect_leading_silence(sound, silence_threshold=-20, chunk_size=10):
    trim_ms = 0

    while sound[trim_ms:trim_ms+chunk_size].dBFS < silence_threshold and trim_ms < len(sound):
        trim_ms += chunk_size

    return trim_ms


def trim_silence(sound):
    start_trim = detect_leading_silence(sound)
    end_trim = detect_leading_silence(sound.reverse())
    duration = len(sound)
    trimmed_sound = sound[start_trim:duration-end_trim]
    return trimmed_sound


file_map = {
    "k": "k.mp3",
    "æ": "ae.mp3",
    "t": "t.mp3",
    "i": "i.mp3",
    "ɛ": "e.mp3",
    "ɪ": "bigI.mp3",
    "ɑ": "revA.mp3",
    "v": "v.mp3",
    "n": "newn.mp3",
    "ŋ": "ng.mp3",
    "h": "h.mp3",
    "s": "s.mp3",
    "z": "z.mp3",
    "ə": "schwa.mp3",
    "r": "r.mp3",
    "p": "p.mp3",
    "m": "m.mp3",
    "θ": "th.mp3",
    "l": "l.mp3",
    "ɔ": "aw.mp3",
    "b": "b.mp3",
    "ð": "thV.mp3",
    "j": "j.mp3",
    "ʒ": "zh.mp3",
    "ʧ": "ch.mp3",
    "ʤ": "dz.mp3",
    "ʃ": "sh.mp3",
    "g": "g.mp3",
    "d": "d.mp3",
    "w": "w.mp3",
    "f": "f.mp3",
    "u": "u.mp3",
    "ʊ": "euh.mp3"
}

double_letters = {
    "eɪ": "ei.mp3",
    "aɪ": "ai.mp3",
    "oʊ": "ou.mp3",
    "aʊ": "aun.mp3",
    "ər": "r.mp3"
}


def main(clips_dir):
    words = input()

    sounds = phrase_to_sound(words)
    print(words.split())
    print(sounds)

    overlap = 20

    out = AudioSegment.empty()
    ploc = 0
    loc = 0
    for s in sounds:
        i = 0
        while i < len(s):
            sound_file = None
            if i < len(s) - 1 and s[i:i+2] in double_letters:
                sound_file = clips_dir + "/" + double_letters.get(s[i:i+2])
                i += 2
            else:
                c = s[i]
                if c in file_map:
                    sound_file = clips_dir + "/" + file_map.get(c, "")
                i += 1
            if sound_file is not None:
                sound = AudioSegment.from_file(sound_file)
                sound = trim_silence(effects.normalize(sound))
                prevlen = loc - ploc
                minlen = min(min(prevlen, len(sound)) // 2, 50)
                if minlen > loc:
                    minlen = loc
                out += AudioSegment.silent(len(sound) - minlen)
                out = out.overlay(sound, loc - minlen)
                ploc = loc
                loc += len(sound) - minlen
        if s[-1] == ".":
            loc += 200
            ploc = loc
            out += AudioSegment.silent(200)
        loc += 40
        ploc = loc
        out += AudioSegment.silent(40)

    play(out)


if __name__ == "__main__":
    main("c:/Users/rzn31/projects/hackbeanpot/clips")
