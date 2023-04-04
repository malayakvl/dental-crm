import { syncsMapper } from "../Sync/normalizer";
import { sentencesMapper } from "../Sentence/normalizer";

export const clinicsMapper = function(chapters) {
  let result = [];

  result = {
    ...result,
    ...chapters.reduce(
        (prev, chapter) => ({
          ...prev,
          [chapter.id]: chapterMapper(chapter)
        }),
        {}
    )
  };

  return result;
};

export const chapterMapper = chapter => ({
    id: chapter.id,
    audioBookId: chapter.audioBookId,
    index: chapter.index,
    name: chapter.name,
    fullName: chapter.fullName,
    artist: chapter.artist,
    sentences: sentencesMapper(chapter.text),
    link: chapter.link,
    duration: chapter.duration,
    sync: chapter.sync ? syncsMapper(chapter.sync) : null,
});
