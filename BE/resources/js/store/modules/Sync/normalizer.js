export const syncsMapper = function(syncs) {
  let result = [];

  result = {
    ...result,
    ...syncs.reduce(
        (prev, sync) => ({
          ...prev,
          [sync.id]: syncMapper(sync)
        }),
        {}
    )
  };

  return result;
};

export const syncMapper = sync =>  ({
    id: sync.id,
    start: sync.start,
    finish: sync.finish,
});
